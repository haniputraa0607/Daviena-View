<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Lib\MyHelper;

class Log
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$scopes): Response
    {
        foreach ($scopes as $scope) {
            switch ($scope) {
                case ('activity'):
                    $bearer_token = session('access_token');
                    $decoded_token = MyHelper::extractToken($bearer_token);
                    $payload_logger = [
                        "user_id" => $decoded_token['id'],
                        "user_email" => $decoded_token['email'],
                        "path" => $request->getPathInfo(),
                        "action" => ucwords(implode(' ', explode('.', $request->route()->getName() ?? null))),
                        "ip_address" => $request->ip(),
                        "user_agent" => $request->header('user-agent')
                    ];
                    MyHelper::post('core-user', 'v1/cms-activity-log', $payload_logger);

                    break;

                default:
                    // no action
            }
        }

        return $next($request);
    }
}
