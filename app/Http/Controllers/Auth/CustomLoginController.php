<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

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
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);
        $loginField = $this->getLoginField($request->input('email'));
        
        // Add the login field to the credentials
        $credentials[$loginField] = $request->input('email');
        unset($credentials['email']);
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Check if user is active
            if (!Auth::user()->isActive()) {
                Auth::logout();
                return $this->sendFailedLoginResponse($request, 'auth.inactive');
            }

            // Check if email is verified if required
            if (config('auth.verify_email') && !Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                return $this->sendFailedLoginResponse($request, 'auth.unverified');
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
    
    /**
     * Determine the login field (email or phone) based on the input.
     *
     * @param  string  $input
     * @return string
     */
    protected function getLoginField($input)
    {
        return filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            'password' => 'required|string',
        ];

        // Check if the input is an email or phone
        $input = $request->input('email');
        $field = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        // Add validation rule based on the field type
        if ($field === 'email') {
            $rules['email'] = 'required|string|email|max:255';
        } else {
            $rules['email'] = 'required|string|regex:/^[0-9+\-\s()]+$/|min:10';
        }

        $request->validate($rules);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $message
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request, $message = 'auth.failed')
    {
        throw ValidationException::withMessages([
            'email' => [trans($message)],
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
