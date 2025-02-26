# A simple package to help integrate Cloudflare Turnstile.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ryangjchandler/laravel-cloudflare-turnstile.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/laravel-cloudflare-turnstile)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ryangjchandler/laravel-cloudflare-turnstile/run-tests.yml?branch=main&style=flat-square&label=tests)](https://github.com/ryangjchandler/laravel-cloudflare-turnstile/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ryangjchandler/laravel-cloudflare-turnstile/fix-php-code-style-issues.yml?branch=main&style=flat-square&label=code+style)](https://github.com/ryangjchandler/laravel-cloudflare-turnstile/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ryangjchandler/laravel-cloudflare-turnstile.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/laravel-cloudflare-turnstile)

This packages provides helper for setting up and validating Cloudflare Turnstile CAPTCHA responses.

## Installation

You can install the package via Composer:

```bash
composer require ryangjchandler/laravel-cloudflare-turnstile
```

You should then add the following configuration values to your `config/services.php` file:

```php
return [

    // ...,

    'turnstile' => [
        'key' => env('TURNSTILE_SITE_KEY'),
        'secret' => env('TURNSTILE_SECRET_KEY'),
    ],

];
```

Visit Cloudflare to create your site key and secret key and add them to your `.env` file.

```
TURNSTILE_SITE_KEY="1x00000000000000000000AA"
TURNSTILE_SECRET_KEY="2x0000000000000000000000000000000AA"
```

## Usage

In your layout file, include the Turnstile scripts using the `@turnstileScripts` Blade directive. This should be added to the `<head>` of your document.

```blade
<html>
    <head>
        @turnstileScripts()
    </head>
    <body>
        {{ $slot }}
    </body>
</html>
```

Once that's done, you can use the `<x-turnstile />` component inside of a `<form>` to output the appropriate markup with your site key configured.

```blade
<form action="/" method="POST">
    <x-turnstile />

    <button>
        Submit
    </button>
</form>
```

On the server, use the provided validation rule to validate the CAPTCHA response.

```php
use Illuminate\Validation\Rule;

public function submit(Request $request)
{
    $request->validate([
        'cf-turnstile-response' => ['required', Rule::turnstile()],
    ]);
}
```

If you don't want to use the macro, you can use the `turnstile` extension rule instead.

```php
use Illuminate\Validation\Rule;

public function submit(Request $request)
{
    $request->validate([
        'cf-turnstile-response' => ['required', 'turnstile'],
    ]);
}
```

You can also inject the rule object and pass it to the list of validation rules directly.

```php
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

public function submit(Request $request, Turnstile $turnstile)
{
    $request->validate([
        'cf-turnstile-response' => ['required', $turnstile],
    ]);
}
```

```php
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

public function submit(Request $request)
{
    $request->validate([
        'cf-turnstile-response' => ['required', app(Turnstile::class)],
    ]);
}
```

### Customizing the widget

You can customize the widget by passing attributes to the `<x-turnstile />` component.

> To learn more about these parameters, refer to [the offical documentation](https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/#configurations).

```blade
<form action="/" method="POST">
    <x-turnstile
        data-action="login"
        data-cdata="sessionid-123456789"
        data-callback="callback"
        data-expired-callback="expiredCallback"
        data-error-callback="errorCallback"
        data-theme="dark"
        data-tabindex="1"
    />

    <button>
        Submit
    </button>
</form>
```

This package can also integrate seamlessly with [Livewire](https://laravel-livewire.com). Upon successful validation, the property specified inside of `wire:model` will be updated with the Turnstile token.

```blade
<x-turnstile wire:model="yourModel" />
```

### Multiple widgets with Livewire

If you're using Livewire and need to have multiple widgets on the page, each widget requires a unique ID.

```blade
<x-turnstile id="my_widget" wire:model="captcha" />
```

The `id` property must match this RegEx: `/^[a-zA-Z_][a-zA-Z0-9_]*$/`. IDs that do not match the RegEx will trigger an exception.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ryan Chandler](https://github.com/ryangjchandler)
- [All contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
