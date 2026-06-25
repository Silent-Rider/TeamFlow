<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

/**
 * Реализован через нативный Laravel-контракт handle(Request, Closure $next),
 * а не PSR-15 (MiddlewareInterface::process()) — Laravel не использует PSR-15
 * "из коробки", и его внедрение требует bridge-слоя (nyholm/psr7 +
 * symfony/psr-http-message-bridge) без практической пользы для этой задачи.
 */
class LocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('app.supported_locales');
        $locale = $request->query('locale');

        if (in_array($locale, $supported, true)) {
            session(['locale' => $locale]);
        } else {
            $locale = session('locale', config('app.locale'));
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
