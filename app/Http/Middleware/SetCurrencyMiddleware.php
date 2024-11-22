<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isEnvTest = config('app.env') === 'testing';
        if ($isEnvTest) {
            return $next($request);
        }

        $segment = $request->segment(1) ?? null;

        if (! $segment) {
            return $next($request);
        }

        $country = explode('-', $request->segment(1))[1];

        if (in_array($country, config('locales.EURCountries'))) {
            $currency = 'EUR';
        } elseif (in_array($country, config('locales.GBPCountries'))) {
            $currency = 'GBP';
        }

        session(['currency' => $currency]);

        return $next($request);
    }
}
