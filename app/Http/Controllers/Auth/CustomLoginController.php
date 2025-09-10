<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CustomLoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     */
    protected $redirectTo = '/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        Log::info('Login attempt started', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'input' => $request->except(['_token', 'password'])
        ]);
        
        try {
            // Validate login input
            $this->validateLogin($request);
            Log::debug('Login validation passed', [
                'email' => $request->input('email'),
                'has_password' => !empty($request->input('password'))
            ]);
            
            // Log the login field being used
            $loginField = $this->getLoginField($request->input('email'));
            Log::debug('Using login field', [
                'field' => $loginField,
                'value' => $request->input('email')
            ]);

            // Check for too many login attempts
            if (method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)) {
                Log::warning('Too many login attempts', [
                    'ip' => $request->ip(),
                    'email' => $request->input('email')
                ]);
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }

            // Prepare credentials for authentication
            $credentials = $this->credentials($request);
            
            // Determine login field (email or username)
            $loginField = $this->getLoginField($request->input('email'));
            $loginValue = $request->input('email');
            
            // Clean phone number if that's what's being used
            if ($loginField === 'phone') {
                $loginValue = preg_replace('/[^0-9]/', '', $loginValue);
            }
            
            $credentials[$loginField] = $loginValue;
            unset($credentials['email']);

            Log::debug('Attempting authentication', [
                'login_field' => $loginField,
                'login_value' => $loginValue,
                'has_remember' => $request->filled('remember'),
                'credentials_keys' => array_keys($credentials)
            ]);

            // Attempt authentication
            $authResult = $this->guard()->attempt($credentials, $request->filled('remember'));
            
            if ($authResult) {
                $user = $this->guard()->user();
                Log::info('Login successful', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'roles' => $user->getRoleNames()->toArray(),
                    'login_field' => $loginField,
                    'session_id' => session()->getId()
                ]);
                return $this->sendLoginResponse($request);
            } else {
                // Log failed authentication
                $user = User::where($loginField, $credentials[$loginField])->first();
                Log::warning('Authentication failed', [
                    'login_field' => $loginField,
                    'login_value' => $credentials[$loginField],
                    'user_exists' => !is_null($user),
                    'ip' => $request->ip()
                ]);
            }

            // Log failed login attempt
            Log::warning('Login failed', [
                'login_field' => $loginField,
                'email' => $request->input('email'),
                'ip' => $request->ip()
            ]);

            $this->incrementLoginAttempts($request);
            
            return $this->sendFailedLoginResponse($request);
            
        } catch (\Exception $e) {
            Log::error('Login error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get the login username/email to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Get the login field (email or username) based on the input.
     *
     * @param  string  $input
     * @return string
     */
    /**
     * Get the login field (email, phone, or username) based on the input.
     *
     * @param  string  $input
     * @return string
     */
    protected function getLoginField($input)
    {
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
        
        // Check if input is a phone number (digits only, at least 10 digits)
        $digits = preg_replace('/[^0-9]/', '', $input);
        if (strlen($digits) >= 10) {
            return 'phone';
        }
        
        // Default to username
        return 'username';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
        } else {
            $rules['email'] = 'required|string|regex:/^[0-9+\-\s()]+$/|min:10';
        }

        $request->validate($rules);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        Log::warning('Sending failed login response', [
            'email' => $request->input('email'),
            'ip' => $request->ip()
        ]);
        
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Check if user is active
        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is not active. Please contact the administrator.']);
        }

        // Redirect based on user role
        if ($user->hasRole('Super Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('CompanyAdmin')) {
            return redirect()->route('company.dashboard');
        } elseif ($user->hasRole('Staff')) {
            return redirect()->route('staff.dashboard');
        } elseif ($user->hasRole('Technician')) {
            return redirect()->route('technician.dashboard');
        } elseif ($user->hasRole('Customer')) {
            return redirect()->route('customer.dashboard');
        }

        return redirect()->intended($this->redirectPath());
    }
}
