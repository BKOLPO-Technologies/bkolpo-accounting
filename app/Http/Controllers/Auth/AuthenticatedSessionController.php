<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse{
        try {
            // Try authenticating the user
            $request->authenticate();

            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // Redirect to the admin dashboard with a success message
            return redirect()->intended(route('admin.dashboard', absolute: false))
                ->with('success', 'Admin Login Successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If authentication fails, return back with an error message
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->with(['error' => 'These credentials do not match our records.']);
        }
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Admin Logout Successfully');;
    }
}
