<?php

namespace RyanChandler\LaravelCloudflareTurnstile;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RyanChandler\LaravelCloudflareTurnstile\Commands\LaravelCloudflareTurnstileCommand;

class LaravelCloudflareTurnstileServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-cloudflare-turnstile')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-cloudflare-turnstile_table')
            ->hasCommand(LaravelCloudflareTurnstileCommand::class);
    }
}
