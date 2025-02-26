<?php

namespace RyanChandler\LaravelCloudflareTurnstile;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ConcreteValidator;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
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
        $this->bootValidation();
    }

    private function bootBlade(): void
    {
        Blade::component('turnstile', TurnstileComponent::class);

        Blade::directive('turnstileScripts', function () {
            return '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
        });
    }

    private function bootValidation(): void
    {
        Rule::macro('turnstile', function () {
            return app(Turnstile::class);
        });

        Validator::extend('turnstile', function (string $attribute, mixed $value, array $parameters, ConcreteValidator $validator) {
            $rule = $this->app->make(Turnstile::class);

            if ($rule->passes($attribute, $value)) {
                return true;
            }

            $validator->setCustomMessages([
                $attribute => $rule->message(),
            ]);

            return false;
        });
    }
}
