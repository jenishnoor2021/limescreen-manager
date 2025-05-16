<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{

    public function login(Request $req)
    {
        // return $req->input();
        $user = User::where(['username' => $req->username])->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return redirect()->back()->with('alert-error', 'Username or password is not matched');
            // return "Username or password is not matched";
        } else {
            Auth::loginUsingId($user->id);
            $req->session()->put('user', $user);
            return redirect('/admin/dashboard');
        }
    }

    public function sendResetLinkEmail(Request $req)
    {
        // Validate Email
        $req->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Find User
        $user = User::where('email', $req->email)->first();

        if (!$user) {
            return redirect()->back()->with('alert-error', 'Email does not exist');
        }

        // Generate Token
        $token = Str::random(60);

        // Store Token in Password Resets Table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $req->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Generate Reset Link
        $resetUrl = url('/password-reset?token=' . $token . '&email=' . urlencode($req->email));

        // Send Email
        Mail::to($req->email)->send(new PasswordResetMail($resetUrl));

        return redirect()->back()->with('alert', 'Reset link sent to your email');
    }

    public function showResetForm(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');

        // Verify the token
        $exists = DB::table('password_resets')->where('email', $email)->where('token', $token)->exists();

        if (!$exists) {
            return redirect('/login')->with('alert-error', 'Invalid or expired reset link.');
        }

        return view('auth.passwords.reset', compact('email', 'token'));
    }

    public function resetPassword(Request $request)
    {
        // Validate request
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        // Check if token exists
        $resetEntry = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetEntry) {
            return redirect()->back()->with('alert-error', 'Invalid or expired token.');
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete reset token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('/login')->with('alert', 'Password has been reset successfully.');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function dashboard()
    {
        return view('admin.index');
    }

    public function profiledit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.profile.edit', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        // $user = User::where('id',1)->first();
        // $user->password = Hash::make($request->new_password);
        // $user->save();
        // return redirect()->back()->with("success","Password changed successfully !");
        // return $request;
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
        $user->save();

        return redirect()->back()->with("success", "Password changed successfully !");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {}

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
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {}
}
