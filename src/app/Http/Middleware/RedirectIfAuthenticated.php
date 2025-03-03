<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * 認証済みのユーザーを各TOPページにリダイレクトする
     * @param  Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @param  string  $guard
     * @param  string  $redirectRoute
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $guard, string $redirectRoute): Response
    {
        if (Auth::guard($guard)->check()) {
            return redirect()->route($redirectRoute);
        }

        return $next($request);
    }
}
