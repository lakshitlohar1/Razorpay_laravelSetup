<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
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

    // Add the following property to redirect after login
    protected $redirectTo = '/index';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        // Validate login credentials
        $this->validateLogin($request);

        // If the user is authenticated
        if ($this->attemptLogin($request)) {
            // Redirect to the dashboard
            return $this->sendLoginResponse($request);
        }

        // If authentication fails
        return $this->sendFailedLoginResponse($request);
    }

    // Validate the user login request
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    }

    // Attempt to log the user into the application
    protected function attemptLogin(Request $request)
    {
        return Auth::attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    // Get the needed authorization credentials from the request
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    // Redirect after a successful login
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath());
    }

    // Where to redirect after login
    public function redirectPath()
    {
        return $this->redirectTo;
    }

    // Send failed login response
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
