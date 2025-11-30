<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session, default to 'hr'
        $locale = Session::get('locale', 'hr');

        // Validate locale (only allow hr and en)
        if (!in_array($locale, ['hr', 'en'])) {
            $locale = 'hr';
        }

        // Set application locale
        App::setLocale($locale);

        return $next($request);
    }
}
