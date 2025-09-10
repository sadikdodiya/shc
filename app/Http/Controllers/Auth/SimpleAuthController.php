<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SimpleAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.simple-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        
        // Try to authenticate
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Redirect based on role
            $user = Auth::user();
            if ($user->hasRole('Super Admin')) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->hasRole('CompanyAdmin')) {
                return redirect()->intended('/company/dashboard');
            } elseif ($user->hasRole('Staff')) {
                return redirect()->intended('/staff/dashboard');
            }
            
            return redirect()->intended('/dashboard');
        }

        // If authentication failed
        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
