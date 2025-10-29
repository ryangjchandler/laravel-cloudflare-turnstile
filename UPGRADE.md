# Upgrading from 2.x to 3.0

This document contains notable breaking changes and the steps required to successfully upgrade your application from 2.x to 3.0.

## `@turnstileScripts` removed in favor of component

The `@turnstileScripts` directive has been removed from the package and replaced with a Blade component instead.

```diff
- @turnstileScripts
+ <x-turnstile.scripts />
```

This syntax is now inline with the Turnstile component itself and improves the consistency of the package.

## `turnstile` rule removed

The `turnstile` stringified validation rule provided this package has now been removed in favor of the `Turnstile` rule object.

```diff
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

$request->validate([
    'cf-turnstile-response' => [
        'required',
-       'turnstile',
+       new Turnstile, 
    ]
]);
```

## `Rule::turnstile()` macro removed

The `Rule::turnstile()` macro provided this package has now been removed in favor of the `Turnstile` rule object.

```diff
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

$request->validate([
    'cf-turnstile-response' => [
        'required',
-       Rule::turnstile(),
+       new Turnstile, 
    ]
]);
```

## `TurnstileClient` class renamed

`TurnstileClient` has been renamed to just `Client`.

```diff
- use RyanChandler\LaravelCloudflareTurnstile\TurnstileClient;
+ use RyanChandler\LaravelCloudflareTurnstile\Client;
```

## `Client` is now a scoped singleton

The `Client` was previously registered as a singleton, but to ensure compatibility with Octane and other single-boot lifecycles, the `Client` is now a scoped singleton.

This shouldn't have a huge impact on userland code, but if you're interacting with this manually there could be breaking changes in behaviour.

Be sure to thoroughly test your application code to find breaking changes.
