<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OptionalAuthSanctum
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken()) {
            $user = Auth::guard('sanctum')->user();
            if ($user) {
                Auth::setUser($user);
                $request->setUserResolver(function () use ($user) {
                    return $user;
                });
                Log::info('User authenticated via Sanctum token', [
                    'user_id' => $user->id,
                    'token' => substr($request->bearerToken(), 0, 10) . '...'
                ]);
            } else {
                Log::warning('Invalid or expired Sanctum token provided', [
                    'token' => substr($request->bearerToken(), 0, 10) . '...'
                ]);
                // Continue without setting a user (optional auth)
            }
        } else {
            Log::debug('No bearer token provided, proceeding as unauthenticated');
        }

        return $next($request);
    }
}
