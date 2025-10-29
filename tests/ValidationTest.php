<?php

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
use RyanChandler\LaravelCloudflareTurnstile\TurnstileClient;

it('can use the rule object for validation', function () {
    config()->set('services.turnstile', [
        'key' => TurnstileClient::SITEKEY_ALWAYS_PASSES_VISIBLE,
        'secret' => TurnstileClient::SECRET_KEY_ALWAYS_PASSES,
    ]);

    $validator = Validator::make([
        'cf-turnstile-response' => TurnstileClient::RESPONSE_DUMMY_TOKEN,
    ], [
        'cf-turnstile-response' => [app(Turnstile::class)],
    ]);

    expect($validator->passes())->toBeTrue();
});
