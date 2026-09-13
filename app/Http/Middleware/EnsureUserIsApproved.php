<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->isApproved()) {
            return $next($request);
        }

        $allowed = ['pending', 'profile.edit', 'profile.update', 'profile.destroy', 'logout', 'verification.notice', 'verification.verify', 'verification.send'];

        if ($request->routeIs($allowed) || $request->is('email/verify*') || $request->is('logout') || $request->is('pending')) {
            return $next($request);
        }

        return redirect()->route('pending');
    }
}
