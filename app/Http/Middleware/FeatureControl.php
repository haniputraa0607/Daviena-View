<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FeatureControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $feature, $feature2 = null)
    {
        if (session('user_role') == 'super_admin') {
            return $next($request);
        }

        $granted = (session('granted_features') != null) ? session('granted_features') : [];
        if (in_array($feature, $granted) || in_array($feature2, $granted)) {
            return $next($request);
        } else {
            return redirect('home')->withErrors(['You don\'t have permission to access the page.']);
        }
    }
}
