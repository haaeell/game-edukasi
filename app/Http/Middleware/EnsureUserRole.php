<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! in_array($user->role, $roles, true)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if ($user->status !== 'active') {
            auth()->logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda sedang tidak aktif.',
            ]);
        }

        return $next($request);
    }
}
