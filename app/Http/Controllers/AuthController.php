<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // =========================
    // TAMPILKAN LOGIN
    // =========================

    public function showLogin()
    {
        return view('login');
    }


    // =========================
    // PROSES LOGIN
    // =========================

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }


    // =========================
    // TAMPILKAN REGISTER
    // =========================

    public function showRegister()
    {
        return view('register');
    }


    // =========================
    // PROSES REGISTER
    // =========================

    public function register(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'min:8',
                'confirmed'
            ],
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,

            // Password TIDAK disimpan sebagai teks biasa
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);


        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }


    // =========================
    // LOGOUT
    // =========================

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}