<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class RestaurantManager
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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->type == "restaurant_manager") {
            return $next($request);
        }

        Auth::logout();
        return redirect()->route('/');
    }
}
