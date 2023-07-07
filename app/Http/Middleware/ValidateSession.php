<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Lib\MyHelper;

class ValidateSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('user_email')) {
            $bearer_token = session('access_token');
            $decoded_token = MyHelper::extractToken($bearer_token);

            if ($decoded_token['expired'] < time()) {
                session()->flush();
                return redirect('login')->withErrors(['Your Session has been expired.']);
            }

            return $next($request);
        } else {
            return redirect('login')->withErrors(['Please login.']);
        }
    }
}
