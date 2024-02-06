<?php

namespace App\Http\Middleware;

use App\Domain\Application\Language\Repositories\LanguageRepository;
use App\Domain\User\Entities\User;
use Closure;
use Illuminate\Http\Request;

class Locale
{
    private LanguageRepository $languageRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    private function preferredLocale(Request $request): ?string
    {
        return $request->getPreferredLanguage($this->languageRepository->all()->pluck('code')->toArray());
    }

    private function userLocale(?User $user): ?string
    {
        return $user && $user->language ? $user->language->code : null;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($code = $this->userLocale($request->user()) ?: $this->preferredLocale($request)) {
            app()->setLocale($code);
        }

        return $next($request);
    }
}
