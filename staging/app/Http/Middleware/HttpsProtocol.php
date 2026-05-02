<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('FORCE_HTTPS') == "On" && !$request->secure()) {
            // Preserve subdirectory path in redirect
            $uri = $request->getRequestUri();
            if (!str_starts_with($uri, '/staging')) {
                $uri = '/staging' . $uri;
            }
            return redirect()->secure($uri);
        }
        return $next($request);
    }
}
