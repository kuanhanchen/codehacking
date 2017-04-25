<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
        // if login
        if(Auth::check()){

            //if is admin
            if(Auth::user()->isAdmin()){

                return $next($request);

            }

        }

        // if not admin or not login,
        // redirect to homepage instead of going to http://localhost/~kuanhanchen/laravel/codehacking/public/admin/users
        return redirect('/');

    }
}
