<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddRefreshTokenToRequest
{
    public function handle(Request $request, Closure $next)
    {
        // Только для POST-запросов к /oauth/token с grant_type=refresh_token
        if ($request->is('oauth/token') && $request->input('grant_type') === 'refresh_token') {
            $refreshToken = $request->cookie('refresh_token');
            if ($refreshToken) {
                // Добавляем параметр refresh_token в тело запроса
                $request->request->add(['refresh_token' => $refreshToken]);
            }
        }
        return $next($request);
    }
}
