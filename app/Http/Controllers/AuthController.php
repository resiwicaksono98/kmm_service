<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function loginPage()
    {
        return Inertia::render('client/Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended('/')->with('toast', "Login Berhasil");
        }

        return redirect()->back()->with('message', 'Email atau password salah');
    }

    public function registerPage()
    {
        return Inertia::render('client/Register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'fullName' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required|min:12|unique:users,phone',
            'address' => 'required',
        ]);

        $user = User::create([
            'full_name' => $request->fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'user',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/')->with('toast', 'Register Berhasil');
        }

        return redirect('/')->with('message', 'Register Gagal');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('toast', 'Logout Berhasil');
    }

    public function adminLoginPage()
    {
        return Inertia::render('admin/Login');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = auth()->user();

            if ($user->role != 'admin') {
                return redirect()->back()->with('message', 'Akses Ditolak');
            }
            return redirect()->intended('/admin')->with('toast', "Login Berhasil");
        }

        return redirect()->back()->with('message', 'Email atau password salah');
    }

    public function workerLoginPage()
    {
        return Inertia::render('client/LoginWorker');
    }

    public function workerLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = auth()->user();

            if ($user->role != 'worker') {
                return redirect()->back()->with('message', 'Akses Ditolak');
            }
            return redirect()->intended('/worker')->with('toast', "Login Berhasil");
        }

        return redirect()->back()->with('message', 'Email atau password salah');
    }

    public function workerLogout()
    {
        Auth::logout();
        return redirect('/worker/login')->with('toast', 'Logout Berhasil');
    }
}
