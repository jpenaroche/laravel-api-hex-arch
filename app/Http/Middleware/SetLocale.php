<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     * Sets User's locale to the system
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = config('app.locale');

        if (Session::has('locale'))
            $locale = Session::get('locale');
        else if(Auth::guard('api')->check())
            $locale = Auth::guard('api')->user()->locale;

        App::setLocale($locale);
//        setlocale(LC_ALL,$locale);

        return $next($request);
    }
}
