<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * 未認証のユーザーを各ログインページにリダイレクトする
     * @param  Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @param  string  $guard
     * @param  string  $redirectRoute
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $guard, string $redirectRoute): Response
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route($redirectRoute);
        }

        return $next($request);
    }
}
