<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {

            if (auth()->user()->status != 'active') {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                Session::flash('error', 'Your account is not active. Please contact your administrator');
                return redirect()->intended('login');
            }


            $request->session()->regenerate();
            if (auth()->user()->role_id == 1) {
                return redirect()->intended('dashboard');
            }

            if (auth()->user()->role_id == 2) {
                return redirect()->intended('profile');
            }
        }
        Session::flash('error', 'Invalid username or password');
        return redirect('login');
    }

    public function register(Request $request)
    {

        return view('auth.register');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            //'password' => 'required|min:8',
            // 'password_confirmation' => 'required|min:8|same:password',
            'phone' => ['min:10', 'max:20', 'numeric', 'unique:users', 'nullable'],
            'address' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = new User();
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->save();
        return redirect('login')->with('success', 'Registration successful');
    }

    public function logout(Request $request)
    {

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
