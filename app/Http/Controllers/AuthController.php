<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password tidak sesuai.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $redirectTo = $request->session()->pull('post_auth_redirect');

        if ($redirectTo) {
            return redirect()->to($redirectTo);
        }

        return redirect()->intended($request->user()->role === 'admin'
            ? route('admin.dashboard')
            : route('user.dashboard'));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'photo' => $data['photo'] ?? null,
            'role' => 'user',
            'status' => 'active',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $redirectTo = $request->session()->pull('post_auth_redirect');

        if ($redirectTo) {
            return redirect()->to($redirectTo)->with('success', 'Register berhasil. Selamat datang!');
        }

        return redirect()->route('user.dashboard')->with('success', 'Register berhasil. Selamat datang!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
