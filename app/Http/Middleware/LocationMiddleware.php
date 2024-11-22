<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocationMiddleware
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

        if (session()->has('locationData')) {
            return $next($request);
        }

        if (config('app.env') === 'local') {
            // TODO: remove this fixed ip for prod
            $data = geoip()->getLocation('146.148.2.0');
        } else {
            $ip = $request->ip();
            $data = geoip()->getLocation($ip);
        }

        if (in_array($data->iso_code, array_keys(config('locales.supportedCountries')))) {
            $location = [
                'country' => $data->iso_code,
                'postalCode' => $data->postal_code,
                'currency' => $data->currency,
                'timezone' => $data->timezone,
            ];
            session(['locationData' => $location]);
            $browserLang = $request->getPreferredLanguage();
            $lang = explode('_', $browserLang)[0];
            $country = session('locationData')['country'];
            $newPath = $lang.'-'.$country.'/'.implode('/', array_slice(explode('/', $request->path()), 1));

            return redirect()->to($newPath);
        }

        return $next($request);
    }
}
