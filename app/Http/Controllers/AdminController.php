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

    public function dashboard()
    {
        $branches = Branch::count();
        $users = User::where('role', '!=', 'Admin')->count();
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $customers = Customer::count();
            $todayleads = Customer::whereDate('created_at', Carbon::today())->count();
        } else if ($loginRole == 'Manager') {
            $loginBranchesId = Auth::user()->branches_id;
            $userIds = User::where('branches_id', $loginBranchesId)->pluck('id');
            $customers = Customer::whereIn('users_id', $userIds)->count();
            $todayleads = Customer::whereDate('created_at', Carbon::today())->whereIn('users_id', $userIds)->count();
        } else {
            $customers = Customer::where('users_id', Session::get('user')->id)->count();
            $todayleads = Customer::whereDate('created_at', Carbon::today())->where('users_id', Session::get('user')->id)->count();
        }

        $oldPendingPayment = Customer::where('balance', '!=', 0)->whereDate('due_date', '<', now())->get();
        $todayPayment = Customer::where('balance', '!=', 0)->whereDate('due_date', now())->get();

        return view('admin.index', compact('branches', 'users', 'customers', 'todayleads', 'oldPendingPayment', 'todayPayment'));
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
    public function index()
    {
        $users = User::where('role', '!=', 'Admin')->get();
        return view('admin.users.index', compact('users'));
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
        if ($loginRole != 'Admin') {
            $branches = Branch::where('id', $loginBrancgId)->get();
        } else {
            $branches = Branch::orderBy('id', 'DESC')->get();
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
            'mobile' => 'required',
            'address' => 'required',
            'email' => ['required', 'email', 'max:255', 'unique:users'],
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
            'mobile' => 'required',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'address' => 'required',
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
