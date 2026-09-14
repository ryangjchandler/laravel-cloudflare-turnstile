<?php

use Illuminate\Support\Facades\Http;
use RyanChandler\LaravelCloudflareTurnstile\Client;
use RyanChandler\LaravelCloudflareTurnstile\Responses\SiteverifyResponse;

it('returns success when siteverify responds 200 with success:true', function () {
    Http::fake([
        'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => true,
            'error-codes' => [],
        ], 200),
    ]);

    $result = (new Client('secret'))->siteverify('valid-token');

    expect($result)->toBeInstanceOf(SiteverifyResponse::class)
        ->and($result->success)->toBeTrue()
        ->and($result->errorCodes)->toBe([]);
});

it('returns failure with error codes when siteverify responds 200 with success:false', function () {
    Http::fake([
        'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => false,
            'error-codes' => ['invalid-input-response'],
        ], 200),
    ]);

    $result = (new Client('secret'))->siteverify('bad-token');

    expect($result->success)->toBeFalse()
        ->and($result->errorCodes)->toContain('invalid-input-response');
});

it('fails closed when the siteverify endpoint is unreachable / non-2xx', function () {
    Http::fake([
        'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response('error', 500),
    ]);

    $result = (new Client('secret'))->siteverify('any-token');

    expect($result->success)->toBeFalse();
});
