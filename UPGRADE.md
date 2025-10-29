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
