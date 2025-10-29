<?php

use Illuminate\Support\Facades\Validator;
use RyanChandler\LaravelCloudflareTurnstile\Client;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

it('can use the rule object for validation', function () {
    config()->set('services.turnstile', [
        'key' => Client::SITEKEY_ALWAYS_PASSES_VISIBLE,
        'secret' => Client::SECRET_KEY_ALWAYS_PASSES,
    ]);

    $validator = Validator::make([
        'cf-turnstile-response' => Client::RESPONSE_DUMMY_TOKEN,
    ], [
        'cf-turnstile-response' => [app(Turnstile::class)],
    ]);

    expect($validator->passes())->toBeTrue();
});
