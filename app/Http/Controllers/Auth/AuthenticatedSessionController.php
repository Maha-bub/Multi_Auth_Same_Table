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
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    // ডিফল্ট ইউআরএল
    $url = "/dashboard";

    // রোল অনুযায়ী ইউআরএল সেট করা
    if ($request->user()->role == "admin") {
        $url = "/admin/dashboard";
    } else if ($request->user()->role == "teacher") {
        $url = "/teacher/dashboard";
    } else if ($request->user()->role == "student") {
        $url = "/student/dashboard";
    }

    // intended() বাদ দিয়ে সরাসরি নির্দিষ্ট ইউআরএল-এ রিডাইরেক্ট করা হলো
    return redirect($url);
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
