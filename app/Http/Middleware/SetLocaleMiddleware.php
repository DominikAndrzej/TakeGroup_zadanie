<?php

namespace App\Http\Middleware;

use App\Enums\SupportedLocale;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Accept-Language');

        if ($locale && in_array($locale, SupportedLocale::values())) {
            app()->setLocale($locale);
        } else {
            app()->setLocale(SupportedLocale::default());
        }

        return $next($request);
    }
}
