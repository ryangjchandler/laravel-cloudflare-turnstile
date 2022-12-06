<?php

namespace RyanChandler\LaravelCloudflareTurnstile;

use Illuminate\Support\Facades\Blade;
use Illuminate\Validation\Rule;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCloudflareTurnstileServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-cloudflare-turnstile')
            ->hasViews();
    }

    public function packageRegistered()
    {
        $this->app->singleton(TurnstileClient::class, function ($app) {
            return new TurnstileClient($app['config']->get('services.turnstile.secret'));
        });
    }

    public function packageBooted()
    {
        Blade::directive('turnstileScripts', function () {
            return '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
        });

        Blade::directive('turnstile', function (string $configurations) {
            return "<?php echo view('cloudflare-turnstile::turnstileWidget')->with('configurations', $configurations); ?>";
        });

        Rule::macro('turnstile', function () {
            return app(Turnstile::class);
        });
    }
}
