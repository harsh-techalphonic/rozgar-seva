<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Crypt;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken()) {
            try {
                $crypt = Crypt::decryptString($request->bearerToken());
                $user = User::find($crypt);
                if ($user) {
                    return $next($request);
                } else {
                    return response()->json(['error' => "Invalid User"], 401);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => "Invalid Token"], 401);
            }
        } else {
            return response()->json(['error' => "Missing Bearer Token"], 403);
        }
    }
}
