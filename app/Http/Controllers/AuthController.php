<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show Views
    public function showLogin() { return view('login'); }
    public function showRegister() { return view('register'); }

    // Handle Account Creation
    public function register(Request $request) {
        // 1. We removed the 'role' rule from validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // 2. We force the role to 'customer' natively
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer' // <--- STRICTLY ENFORCED HERE
        ]);

        Auth::login($user); // Log them in immediately
        return redirect('/');
    }

    // Handle Login
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect based on their role!
            $role = Auth::user()->role;
            if($role == 'admin') return redirect('/admin');
            if($role == 'driver') return redirect('/driver');
            return redirect('/'); // Customer goes home
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    // Handle Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}