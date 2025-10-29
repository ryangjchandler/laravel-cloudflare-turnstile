<?php

namespace RyanChandler\LaravelCloudflareTurnstile;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ConcreteValidator;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
use RyanChandler\LaravelCloudflareTurnstile\View\Components\Scripts;
use RyanChandler\LaravelCloudflareTurnstile\View\Components\Turnstile as TurnstileComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCloudflareTurnstileServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-cloudflare-turnstile')
            ->hasTranslations()
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(TurnstileClient::class, function ($app) {
            return new TurnstileClient($app['config']->get('services.turnstile.secret'));
        });
    }

    public function packageBooted(): void
    {
        $this->bootBlade();
    }

    private function bootBlade(): void
    {
        Blade::component('turnstile.scripts', Scripts::class);
        Blade::component('turnstile', TurnstileComponent::class);
    }
}
