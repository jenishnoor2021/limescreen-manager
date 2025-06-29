<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\UserImages;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function login(Request $req)
    {
        // return $req->input();
        $user = User::where(['username' => $req->username])->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return redirect()->back()->with('alert', 'Username or password is not matched');
            // return "Username or password is not matched";
        } else {
            if ($user->is_active == 1) {
                Auth::loginUsingId($user->id);
                $req->session()->put('user', $user);
                return redirect('/admin/dashboard');
            } else {
                return redirect()->back()->with('alert', 'Your account is not activated. Please contact to administrator!!');
            }
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function dashboard(Request $request)
    {
        $loginUser = Auth::user();
        $loginRole = $loginUser->role;
        $branchId = $loginUser->branches_id ?? null;
        $userId = $loginUser->id ?? null;

        $branchesQuery = Branch::query();
        $branchesCount = Branch::count();
        $users = User::where('role', '!=', 'Admin')->count();

        // Prepare base queries
        $customerQuery = Customer::query();

        if ($loginRole === 'Admin') {
            // No filters needed
        } elseif ($loginRole === 'BreanchHead') {

            // Get managers under the branch
            $userIds = User::where('branches_id', $branchId)
                ->where('role', 'Manager')
                ->pluck('id');

            $customerQuery->whereIn('users_id', $userIds)
                ->where('branches_id', $branchId);

            $branchesQuery->where('id', $branchId);
        } elseif ($loginRole === 'Manager') {

            $customerQuery->where('users_id', $userId)
                ->where('branches_id', $branchId);

            $branchesQuery->where('id', $branchId);
        } else {
            $customerQuery->where('users_id', $userId);
        }

        if ($request->filled('branches_id') && $request->branches_id !== 'ALL') {
            $customerQuery->where('branches_id', $request->branches_id);
        }

        if ($request->filled('users_id') && $request->users_id !== 'ALL') {
            $customerQuery->where('users_id', $request->users_id);
        }

        // Fetch values
        $totalClients = (clone $customerQuery)->count();
        $todayClients = (clone $customerQuery)->whereDate('created_at', Carbon::today())->count();

        $oldPendingPayment = (clone $customerQuery)
            ->where('balance', '!=', 0)
            ->whereDate('due_date', '<', now())
            ->get();

        $todayPayment = (clone $customerQuery)
            ->where('balance', '!=', 0)
            ->whereDate('due_date', now())
            ->get();

        $tommorowPayment = (clone $customerQuery)
            ->where('balance', '!=', 0)
            ->whereDate('due_date', Carbon::tomorrow())
            ->get();

        $branches = $branchesQuery->get();
        $today = Carbon::today()->toDateString();

        $totalCollectionQuery = DB::table('payments')
            ->join('customers', 'payments.customers_id', '=', 'customers.id');

        if ($loginRole === 'BreanchHead') {
            $totalCollectionQuery->where('customers.branches_id', $branchId);
        } elseif ($loginRole === 'Manager') {
            $totalCollectionQuery->where('customers.users_id', $userId);
        }

        $totalCollection = $totalCollectionQuery
            ->select('customers.branches_id', DB::raw('SUM(payments.amount) as total_amount'))
            ->groupBy('customers.branches_id')
            ->pluck('total_amount', 'customers.branches_id');

        // Today's collection
        $todayCollectionQuery = DB::table('payments')
            ->join('customers', 'payments.customers_id', '=', 'customers.id')
            ->whereDate('payments.date', $today);

        if ($loginRole === 'BreanchHead') {
            $todayCollectionQuery->where('customers.branches_id', $branchId);
        } elseif ($loginRole === 'Manager') {
            $todayCollectionQuery->where('customers.users_id', $userId);
        }

        $todayCollection = $todayCollectionQuery
            ->select('customers.branches_id', DB::raw('SUM(payments.amount) as today_amount'))
            ->groupBy('customers.branches_id')
            ->pluck('today_amount', 'customers.branches_id');

        // Received collection
        $recivedCollectionQuery = DB::table('customers');

        if ($loginRole === 'BreanchHead') {
            $recivedCollectionQuery->where('customers.branches_id', $branchId);
        } elseif ($loginRole === 'Manager') {
            $recivedCollectionQuery->where('customers.users_id', $userId);
        }

        $recivedCollection = $recivedCollectionQuery
            ->select('customers.branches_id', DB::raw('SUM(customers.package_amount) as recived_amount'))
            ->groupBy('customers.branches_id')
            ->pluck('recived_amount', 'customers.branches_id');

        // Pending collection
        $pendingCollectionQuery = DB::table('customers');

        if ($loginRole === 'BreanchHead') {
            $pendingCollectionQuery->where('customers.branches_id', $branchId);
        } elseif ($loginRole === 'Manager') {
            $pendingCollectionQuery->where('customers.users_id', $userId);
        }

        $pendingCollection = $pendingCollectionQuery
            ->select('customers.branches_id', DB::raw('SUM(customers.balance) as pending_amount'))
            ->groupBy('customers.branches_id')
            ->pluck('pending_amount', 'customers.branches_id');

        return view('admin.index', compact(
            'branchesCount',
            'users',
            'totalClients',
            'todayClients',
            'oldPendingPayment',
            'todayPayment',
            'branches',
            'totalCollection',
            'todayCollection',
            'recivedCollection',
            'pendingCollection',
            'tommorowPayment'
        ));
    }

    public function profiledit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.profile.edit', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Session::get('user');
        if (!(Hash::check($request->get('current_password'), $user->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }

        if (strcmp($request->get('current_password'), $request->get('new_password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Session::get('user');
        $user->password = bcrypt($request->get('new_password'));
        $user->original_password = $request->get('new_password');
        $user->save();

        return redirect()->back()->with("success", "Password changed successfully !");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branches = Branch::orderBy('id', 'DESC')->get();
        $loginUser = Session::get('user');
        $loginRole = $loginUser->role;
        $loginID = $loginUser->id;
        $loginBranchId = $loginUser->branches_id;

        $usersQuery = User::query();

        if ($loginRole == 'Admin') {
            $usersQuery->where('role', '!=', 'Admin')->orderBy('role', 'asc');

            if ($request->filled('branch')) {
                $usersQuery->where('branches_id', $request->branch);
            }

            if ($request->filled('selectType') && $request->selectType !== 'ALL') {
                $usersQuery->where('role', $request->selectType);
            }
        } elseif ($loginRole == 'BreanchHead') {
            $usersQuery->where('branches_id', $loginBranchId)
                ->where('role', 'Manager');
        } else {
            $usersQuery->where('id', $loginID);
        }

        $users = $usersQuery->get();

        return view('admin.users.index', compact('users', 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loginRole = Session::get('user')->role;
        $loginBrancgId = Session::get('user')->branches_id;
        if ($loginRole == 'Admin') {
            $branches = Branch::orderBy('id', 'DESC')->get();
        } elseif ($loginRole == 'BreanchHead') {
            $branches = Branch::where('id', $loginBrancgId)->get();
        } else {
            $branches = Branch::where('id', $loginBrancgId)->get();
        }
        return view('admin.users.create', compact('branches'));
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
            'branches_id' => 'required',
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();
        $input['password'] = bcrypt($request->password);
        $input['original_password'] = $request->password;
        User::create($input);

        Session::flash('message', "Record Save Successfully");
        return redirect('admin/users');
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
        $branches = Branch::orderBy('id', 'DESC')->get();
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user', 'branches'));
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
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'branches_id' => 'required',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();

        if (!empty($request->password)) {
            $input['password'] = bcrypt($request->password);
            $input['original_password'] = $request->password;
        }

        $user->update($input);

        Session::flash('message', "Record updated Successfully");
        return redirect('admin/users');
        // return redirect('admin/posts/edit/' . $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        Customer::where('users_id', $id)->delete();
        $user->delete();

        Session::flash('message', "Record deleted Successfully");
        return Redirect::back();
    }

    public function accountActive(Request $request)
    {
        $user = User::find($request->id);

        if ($user) {
            $user->is_active = !$user->is_active; // Toggle the status
            $user->save();

            return response()->json([
                'success' => true,
                'status' => $user->is_active ? 'Active' : 'De-active'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Agent not found!']);
    }
}
