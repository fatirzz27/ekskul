<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login process.
     */
  public function store(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required','email'],
        'password' => ['required'],
    ]);

    if (! Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    $request->session()->regenerate();

    $user = Auth::user();

    // Redirect sesuai role
    if ($user->role === 'admin') {
        return redirect()->route('dashboard')->with('success', 'Welcome Admin!');
    } elseif ($user->role === 'pembina') {
        return redirect()->route('dashboard')->with('success', 'Welcome Pembina!');
    } elseif ($user->role === 'siswa') {
        return redirect()->route('dashboard')->with('success', 'Welcome Siswa!');
    } else {
        // fallback kalau role belum diatur
        return redirect()->route('dashboard');
    }
}


    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
