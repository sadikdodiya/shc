<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FixedLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function login(Request $request)
    {
        try {
            $this->validateLogin($request);

            // Log login attempt
            Log::info('Login attempt', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Determine login field (email or phone)
            $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            $login = $request->email;
        
        // Clean phone number if needed
        if ($field === 'phone') {
            $login = preg_replace('/[^0-9]/', '', $login);
        }

        // Attempt to find the user
        $user = User::where($field, $login)->first();

        // Check if user exists and is active
        if (!$user) {
            Log::warning('Login failed: User not found', ['login' => $login]);
            return $this->sendFailedLoginResponse($request);
        }
        
        if ($user->status != 1) {
            Log::warning('Login failed: User not active', ['user_id' => $user->id]);
            return $this->sendFailedLoginResponse($request);
        }

        // Verify password and log in the user
        if (Auth::attempt([$field => $login, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();
            
            Log::info('Login successful', ['user_id' => $user->id]);
            
            // Redirect based on user role
            if ($user->hasRole('Super Admin')) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->hasRole('CompanyAdmin')) {
                return redirect()->intended('/company/dashboard');
            } elseif ($user->hasRole('Staff')) {
                return redirect()->intended('/staff/dashboard');
            }
            
            return redirect()->intended($this->redirectPath());
        }

        // If login failed
        Log::warning('Login failed: Invalid credentials', ['user_id' => $user->id]);
        return $this->sendFailedLoginResponse($request);
    } catch (\Exception $e) {
        Log::error('Login error: ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->withErrors([
            'email' => 'An error occurred during login. Please try again.'
        ]);
    }
}

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
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
