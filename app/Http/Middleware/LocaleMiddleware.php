<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('app.supported_locales');

        $locale = $request->query('locale');
        if (in_array($locale, $supported, true)) {
            session(['locale' => $locale]);
        }

        $locale = session('locale', config('app.locale'));

        app()->setLocale($locale);

        return $next($request);
    }
}
