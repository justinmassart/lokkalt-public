<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class SetFilamentLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Route::getCurrentRoute()->uri === 'dashboard-login') {
            $preferredLang = $request->getPreferredLanguage();
            $lang = explode('_', $preferredLang)[0];
            app()->setLocale($lang);
        }

        return $next($request);
    }
}
