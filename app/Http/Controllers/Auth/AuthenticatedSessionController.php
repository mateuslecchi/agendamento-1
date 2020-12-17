<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Enum\GroupRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\AuthenticatedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    use AuthenticatedUser;

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return match ($this->authGroupRole()->getValue()) {
            GroupRoles::ADMIN()->getValue() => redirect()->route('dashboard'),
            GroupRoles::MANAGER()->getValue() => redirect()->route('dashboard'),
        default => redirect()->route(__('route.schedules.uri')),
        };
        //return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
