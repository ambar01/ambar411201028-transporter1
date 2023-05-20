<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

use Closure;

class AdminTokenMiddleware
{
    public function handle($request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader || !$this->isValidToken($authorizationHeader)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

    private function isValidToken($authorizationHeader)
    {
        $token = str_replace('Admin ', '', $authorizationHeader);

        try {
            $payload = JWTAuth::manager()->decode(new Token($token));
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Add any additional validation checks based on your requirements

        return true;
    }
}
