<?php

use Illuminate\Support\Facades\Validator;
use RyanChandler\LaravelCloudflareTurnstile\Client;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
use RyanChandler\LaravelCloudflareTurnstile\Facades\Turnstile as Facade;

it('can use the rule object for validation', function () {
    Facade::fake();

    $validator = Validator::make([
        'cf-turnstile-response' => Facade::dummy(),
    ], [
        'cf-turnstile-response' => [app(Turnstile::class)],
    ]);

    expect($validator->passes())->toBeTrue();
});

it('fails validation with an invalid token', function () {
    Facade::fake()->fail();

    $validator = Validator::make([
        'cf-turnstile-response' => Facade::dummy(),
    ], [
        'cf-turnstile-response' => [app(Turnstile::class)],
    ]);

    expect($validator->fails())->toBeTrue();
});
