<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Session;
use Config;

class ApiLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {
    //     // Check header request and determine localizaton
    //     if($request->hasHeader('Accept-Language')){
    //         $locale = $request->header('Accept-Language');
    //     }
    //     elseif(env('DEFAULT_LANGUAGE') != null){
    //         $locale = env('DEFAULT_LANGUAGE');
    //     }
    //     else{
    //         $locale = 'en';
    //     }

    //     // set laravel localization
    //     app()->setLocale($locale);

    //     // continue request
    //     return $next($request);
    // }

    public function handle($request, Closure $next)
    {
        // Check header request and determine localization
        if ($request->hasHeader('Accept-Language')) {
            $localeHeader = $request->header('Accept-Language');

            // Example: "en_US,en;q=0.9" -> ["en_US","en;q=0.9"]
            $locale = explode(',', $localeHeader)[0];

            // Remove any ";q=..." part (if exists)
            $locale = explode(';', $locale)[0];
        }
        elseif (env('DEFAULT_LANGUAGE') != null) {
            $locale = env('DEFAULT_LANGUAGE');
        }
        else {
            $locale = 'en';
        }

        // Set laravel localization
        app()->setLocale($locale);

        // Continue request
        return $next($request);
    }
}
