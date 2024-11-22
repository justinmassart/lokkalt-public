<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /* $location = geoip()->getLocation($request->ip()); */
        $lang = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

        app()->setLocale($lang);

        $segments = $request->segments();

        if (count($segments) >= 2 /* && $segments[0] === $location->iso_code */ && $segments[1] === $lang) {
            return $next($request);
        }

        $newUrl = $request->getSchemeAndHttpHost() /* . '/' . $location->iso_code */.'/'.$lang.'/'.implode('/', $segments);

        return Redirect::to($newUrl);
    }
}
