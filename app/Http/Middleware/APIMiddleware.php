<?php

namespace App\Http\Middleware;

use Closure;
use Response;
use App\User;

class APIMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		header('Access-Control-Allow-Origin: *');

        if(!$request->token) {
            return Response::json([
                "status" => "NOT_OK",
                "response" => "A user token is required to access this route."
            ], 400);
        }

        $user = User::where('remember_token', $request->token)->first();

        if(!$user) {
            return Response::json([
                "status" => "NOT_OK",
                "response" => "User not found!"
            ], 404);
        }

        return $next($request)->header('Access-Control-Allow-Origin', '*');
    }
}
