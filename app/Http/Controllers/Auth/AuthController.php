<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string',
                'password' => 'required|string',
            ]);

            $credentials = $request->only('email', 'password');
            
            // Determine if login is by email or phone
            $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            $login = $credentials['email'];
            
            // Clean phone number if needed
            if ($field === 'phone') {
                $login = preg_replace('/[^0-9]/', '', $login);
            }

            // Find the user
            $user = User::where($field, $login)->first();

            // Check if user exists and is active
            if (!$user || $user->status != 1) {
                throw ValidationException::withMessages([
                    'email' => 'These credentials do not match our records.'
                ]);
            }

            // Attempt to authenticate
            if (Auth::attempt([$field => $login, 'password' => $credentials['password']], $request->filled('remember'))) {
                $request->session()->regenerate();
                
                // Redirect based on role
                if ($user->hasRole('Super Admin')) {
                    return redirect()->intended('/admin/dashboard');
                } elseif ($user->hasRole('CompanyAdmin')) {
                    return redirect()->intended('/company/dashboard');
                } elseif ($user->hasRole('Staff')) {
                    return redirect()->intended('/staff/dashboard');
                }
                
                return redirect()->intended('/dashboard');
            }

            throw ValidationException::withMessages([
                'email' => 'The provided credentials are incorrect.'
            ]);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage(), [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'email' => 'An error occurred during login. Please try again.'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
