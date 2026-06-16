<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Locale is enabled and allowed to be changed
        if(!$request->hasHeader("Accept-Language")){
            $request->headers->set('Accept-Language',env('DEFAULT_LOCALE','en'));
        }
        if (config('boilerplate.locale.status')) {
            $acceptLanguage = $request->header("Accept-Language");
            // Parse Accept-Language header to extract first locale
            // Format: "en_GB,en_US;q=0.9,en;q=0.8" -> extract "en_GB"
            $locale = $this->parseAcceptLanguage($acceptLanguage);
            setAllLocale($locale);
        }
        return $next($request);
    }

    /**
     * Parse Accept-Language header to extract the first preferred locale
     *
     * @param string $acceptLanguage
     * @return string
     */
    private function parseAcceptLanguage($acceptLanguage)
    {
        // Split by comma to get individual locales
        $locales = explode(',', $acceptLanguage);
        // Get the first locale (most preferred)
        $firstLocale = trim($locales[0]);
        // Remove quality value if present (e.g., "en;q=0.9" -> "en")
        $locale = explode(';', $firstLocale)[0];
        
        // Normalize locale format to match config
        // Convert hyphens to underscores (en-GB -> en_GB)
        $locale = str_replace('-', '_', $locale);
        
        // Check if the full locale exists in config, if not, try language-only version
        $availableLocales = array_keys(config('boilerplate.locale.languages', []));
        if (!in_array($locale, $availableLocales)) {
            // Extract language part (en_GB -> en)
            $languageOnly = explode('_', $locale)[0];
            if (in_array($languageOnly, $availableLocales)) {
                $locale = $languageOnly;
            } else {
                // Fall back to default locale
                $locale = env('DEFAULT_LOCALE', 'en');
            }
        }
        
        return $locale;
    }
}
