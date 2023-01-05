<?php

namespace App\Http\Middleware;

use Closure;

class Customer
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
        $user = $request->user();

        if ($user && $user->role->slug == 'customer') {
            return $next($request);
        }

        return response()->json([
            'error' => 'Unauthorized access.'
        ], 401);
    }
}
