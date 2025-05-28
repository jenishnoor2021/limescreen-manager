<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\Branch;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Exports\PaymentExport;
use Illuminate\Support\Carbon;
use App\Exports\CustomersExport;
use Illuminate\Support\Facades\DB;
use App\Exports\ClientPaymentExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AdminCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->input('date_filter', 'today');

        // Start building the query
        $query = Customer::query();

        // Role-based data scope
        if (Auth::user()->role === 'Admin') {
            // No restriction
        } elseif (Auth::user()->role === 'Manager') {
            $loginBranchesId = Auth::user()->branches_id;
            $userIds = User::where('branches_id', $loginBranchesId)->pluck('id');
            $query->where('branches_id', $loginBranchesId)->whereIn('users_id', $userIds);
        } else {
            $query->where('users_id', Auth::user()->id);
        }

        // Date filtering
        switch ($filter) {
            case 'yesterday':
                $query->whereDate('created_at', Carbon::yesterday());
                break;

            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;

            case 'last_week':
                $query->whereBetween('created_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ]);
                break;

            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;

            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                    ->whereYear('created_at', Carbon::now()->subMonth()->year);
                break;

            case 'custom':
                $start = $request->input('start_date');
                $end = $request->input('end_date');
                if ($start && $end) {
                    $query->whereBetween('created_at', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()]);
                }
                break;

            case 'all':
                // No date filter applied
                break;

            case 'today':
            default:
                $query->whereDate('created_at', Carbon::today());
                break;
        }

        // Final data fetch
        $customers = $query->orderBy('id', 'DESC')->get();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::orderBy('id', 'DESC')->get();
        $users = User::orderBy('id', 'DESC')->get();
        $packages = Package::orderBy('id', 'DESC')->get();
        return view('admin.customers.create', compact('branches', 'users', 'packages'));
    }

    public function getUsersByBranch($branch_id)
    {
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $users = User::where('branches_id', $branch_id)->get();
        } else {
            $users = User::where('branches_id', $branch_id)->where('role', 'Manager')->get();
        }
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kid_name' => ['required'],
            'father_name' => ['required'],
            'mother_name' => ['required'],
            'email' => ['required'],
            'mobile' => ['required'],
            'whatsapp_number' => ['required'],
            'package' => ['required'],
            'package_amount' => ['required'],
            'advanced' => ['required'],
            'balance' => ['required'],
            'due_date' => ['required'],
            'users_id' => ['required'],
            'branches_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();

        $link = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000, // Version 4
            mt_rand(0, 0x3fff) | 0x8000, // Variant
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );

        $input = $request->except('file');

        $input['link'] = $link;

        $customer = Customer::create($input);

        Payment::create([
            'customers_id' => $customer->id,
            'date' => now()->toDateString(),
            'amount' => $request->advanced,
            'users_id' => Session::get('user')->id,
        ]);

        if ($request->hasFile('file')) {

            $documentsPath = public_path('documents');
            if (!File::exists($documentsPath)) {
                File::makeDirectory($documentsPath, 0755, true); // recursive = true
            }

            foreach ($request->file('file') as $index => $file) {
                if ($file->isValid()) {
                    $imageName = 'document_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('documents'), $imageName);

                    Document::create([
                        'customers_id' => $customer->id,
                        'image' => $imageName,
                    ]);
                }
            }
        }

        return redirect('admin/customers')->with('success', "Add Record Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $branches = Branch::orderBy('id', 'DESC')->get();
        $users = User::orderBy('id', 'DESC')->get();
        $customerDocs = Document::where('customers_id', $id)->get();
        $packages = Package::orderBy('id', 'DESC')->get();
        return view('admin.customers.edit', compact('customer', 'branches', 'users', 'customerDocs', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'kid_name' => ['required'],
            'father_name' => ['required'],
            'mother_name' => ['required'],
            'email' => ['required'],
            'mobile' => ['required'],
            'whatsapp_number' => ['required'],
            'package' => ['required'],
            'package_amount' => ['required'],
            'advanced' => ['required'],
            'balance' => ['required'],
            'due_date' => ['required'],
            'users_id' => ['required'],
            'branches_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $customer = Customer::findOrFail($id);
        $input = $request->except(['file', 'existing_images']);
        $customer->update($input);

        $keepImages = $request->input('existing_images', []);
        $existingDocs = Document::where('customers_id', $id)->get();

        foreach ($existingDocs as $doc) {
            if (!in_array($doc->image, $keepImages)) {
                $docPath = public_path('documents/' . $doc->image);
                if (File::exists($docPath)) {
                    File::delete($docPath);
                }
                $doc->delete();
            }
        }

        if ($request->has('file')) {
            foreach ($request->file('file') as $index => $file) {
                if ($file->isValid()) {
                    $imageName = 'document_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('documents'), $imageName);

                    Document::create([
                        'customers_id' => $customer->id,
                        'image' => $imageName,
                    ]);
                }
            }
        }

        $this->calculateBalance($customer->id, $request->package_amount);

        return redirect('admin/customers')->with('success', "Update Record Successfully");
    }

    function calculateBalance($id, $packAmt)
    {
        $total = Payment::where('customers_id', $id)->sum('amount');
        $balance = $packAmt - $total;

        $customer = Customer::findOrFail($id);
        $customer->update(['advanced' => $total, 'balance' => $balance]);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        Document::where('customers_id ', $id)->delete();
        Payment::where('customers_id ', $id)->delete();
        $customer->delete();

        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function report()
    {
        $loginBranchesId = Session::get('user')->branches_id;
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $branches = Branch::orderBy('id', 'DESC')->get();
        } else {
            $branches = Branch::where('id', $loginBranchesId)->get();
        }
        $data = [];
        return view('admin.customers.report', compact('branches', 'data'));
    }

    public function exportShow(Request $request)
    {
        $loginBranchesId = Session::get('user')->branches_id;
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $branches = Branch::orderBy('id', 'DESC')->get();
        } else {
            $branches = Branch::where('id', $loginBranchesId)->get();
        }

        $startDate = null;
        $endDate = null;

        switch ($request->date_filter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;

            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;

            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;

            case 'last_week':
                $startDate = Carbon::now()->subWeek()->startOfWeek();
                $endDate = Carbon::now()->subWeek()->endOfWeek();
                break;

            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;

            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;

            case 'custom':
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                break;

            case 'all':
            default:
                $startDate = null;
                $endDate = null;
                break;
        }

        $data = Customer::query()
            ->when(
                $request->filled('branches_id') && $request->branches_id !== 'ALL',
                fn($q) =>
                $q->where('branches_id', $request->branches_id)
            )
            ->when(
                $request->filled('users_id') && $request->users_id !== 'ALL',
                fn($q) =>
                $q->where('users_id', $request->users_id)
            )
            ->when(
                is_array($request->verified) && count($request->verified),
                fn($q) =>
                $q->whereIn('is_verified', $request->verified)
            )
            ->when(
                $startDate,
                fn($q) =>
                $q->whereDate('created_at', '>=', $startDate)
            )
            ->when(
                $endDate,
                fn($q) =>
                $q->whereDate('created_at', '<=', $endDate)
            )
            ->get();

        if ($request->has('download')) {
            $fileName = time() . '_client.xlsx';  // Change file extension to .xlsx
            return Excel::download(new CustomersExport($data), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }
        return view('admin.customers.report', compact('branches', 'data'));
    }

    public function paymentReport()
    {
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $branches = Branch::orderBy('id', 'DESC')->get();
        } else {
            $loginBranchesId = Session::get('user')->branches_id;
            $branches = Branch::where('id', $loginBranchesId)->get();
        }
        $data = [];
        return view('admin.customers.payment_report', compact('branches', 'data'));
    }

    public function paymentExportShow(Request $request)
    {
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $branches = Branch::orderBy('id', 'DESC')->get();
        } else {
            $loginBranchesId = Session::get('user')->branches_id;
            $branches = Branch::where('id', $loginBranchesId)->get();
        }

        $startDate = null;
        $endDate = null;

        switch ($request->date_filter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;

            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;

            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;

            case 'last_week':
                $startDate = Carbon::now()->subWeek()->startOfWeek();
                $endDate = Carbon::now()->subWeek()->endOfWeek();
                break;

            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;

            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;

            case 'custom':
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                break;

            case 'all':
            default:
                $startDate = null;
                $endDate = null;
                break;
        }

        $clientIDs = Customer::query()
            ->when(
                $request->filled('branches_id') && $request->branches_id !== 'ALL',
                fn($q) =>
                $q->where('branches_id', $request->branches_id)
            )
            ->when(
                $request->filled('users_id') && $request->users_id !== 'ALL',
                fn($q) =>
                $q->where('users_id', $request->users_id)
            )
            ->when(
                $startDate,
                fn($q) =>
                $q->whereDate('created_at', '>=', $startDate)
            )
            ->when(
                $endDate,
                fn($q) =>
                $q->whereDate('created_at', '<=', $endDate)
            )
            ->pluck('id');

        $data = Payment::whereIn('customers_id', $clientIDs)->get();

        if ($request->has('download')) {
            $fileName = time() . '_payment.xlsx';  // Change file extension to .xlsx
            return Excel::download(new PaymentExport($data), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }
        return view('admin.customers.payment_report', compact('branches', 'data'));
    }

    public function clientPaymentReport()
    {
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $branches = Branch::orderBy('id', 'DESC')->get();
        } else {
            $loginBranchesId = Session::get('user')->branches_id;
            $branches = Branch::where('id', $loginBranchesId)->get();
        }
        $data = [];
        return view('admin.customers.client_payment_report', compact('branches', 'data'));
    }

    public function clientPaymentExportShow(Request $request)
    {
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $branches = Branch::orderBy('id', 'DESC')->get();
        } else {
            $loginBranchesId = Session::get('user')->branches_id;
            $branches = Branch::where('id', $loginBranchesId)->get();
        }

        $startDate = null;
        $endDate = null;

        switch ($request->date_filter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;

            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;

            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;

            case 'last_week':
                $startDate = Carbon::now()->subWeek()->startOfWeek();
                $endDate = Carbon::now()->subWeek()->endOfWeek();
                break;

            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;

            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;

            case 'custom':
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                break;

            case 'all':
            default:
                $startDate = null;
                $endDate = null;
                break;
        }

        $data = Customer::query()
            ->when(
                $request->filled('branches_id') && $request->branches_id !== 'ALL',
                fn($q) =>
                $q->where('branches_id', $request->branches_id)
            )
            ->when(
                $request->filled('users_id') && $request->users_id !== 'ALL',
                fn($q) =>
                $q->where('users_id', $request->users_id)
            )
            ->when(
                $startDate,
                fn($q) =>
                $q->whereDate('created_at', '>=', $startDate)
            )
            ->when(
                $endDate,
                fn($q) =>
                $q->whereDate('created_at', '<=', $endDate)
            )
            ->get();

        if ($request->has('download')) {
            $fileName = time() . '_clientPayment.xlsx';  // Change file extension to .xlsx
            return Excel::download(new ClientPaymentExport($data), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }
        return view('admin.customers.client_payment_report', compact('branches', 'data'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided.']);
        }

        Customer::whereIn('id', $ids)->delete();
        Document::whereIn('customers_id ', $ids)->delete();
        Payment::whereIn('customers_id ', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected ids deleted successfully.']);
    }

    public function showData($slug)
    {
        $customer = Customer::where('link', $slug)->first();
        if ($customer) {
            $documents = Document::where('customers_id', $customer->id)->get();
            return view('front.show', compact('customer', 'documents'));
        } else {
            return view('front.error');
        }
    }

    public function sendOtpApiCall(Request $request)
    {
        $randomNumber = random_int(100000, 999999);
        Session::put('send_otp', $randomNumber);
        $key = "oYoZWIt6auyYWKFB";
        $mbl = $request->mobile;
        $message_content = "Dear User, {" . $randomNumber . "} is your OTP to register on Limecreen. wtf.digital";

        $senderid = "LIMSCR";
        $response = Http::get("http://46.4.104.219/vb/apikey.php?apikey=$key&senderid=$senderid&number=$mbl&message=$message_content");

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $sessionOtp = Session::get('send_otp');
        if ($sessionOtp == $request->otp) {
            Session::forget('send_otp');
            $customer = Customer::find($request->id);
            if ($customer) {
                $customer->update([
                    'is_verified' => 1,
                    'verified_at' => now()
                ]);
            }
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Invalid OTP']);
    }

    public function showCustomerDetail($id)
    {
        $customer = Customer::findOrFail($id);
        return view('partials.customer_detail', compact('customer'));
    }
}
