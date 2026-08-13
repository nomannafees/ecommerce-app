<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class AdminLoginController extends Controller
{
    use ThrottlesLogins;

    // Throttling ke liye username method lazmi hai
    public function username()
    {
        return 'email';
    }

    public function showLoginForm()
    {
        return view('admin_info.login');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->role !== 'admin') {
            $this->incrementLoginAttempts($request);
            return back()->withErrors([
                'email' => 'Access denied. Only administrators can log in here.',
            ])->withInput($request->only('email'));
        }

        if (Auth::guard('web')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);

            return redirect()->intended('/admin/home');
        }

        $this->incrementLoginAttempts($request);

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
