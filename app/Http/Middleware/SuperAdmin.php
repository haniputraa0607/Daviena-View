<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('user_role')) {
            if (session('user_role') != 'super_admin') {
                return back()->withErrors(["You don't have access to the page!"]);
            }
        } else {
            return back()->withErrors(["You don't have access to the page!"]);
        }

        return $next($request);
    }
}
