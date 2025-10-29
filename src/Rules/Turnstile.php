<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use RyanChandler\LaravelCloudflareTurnstile\Client;

class Turnstile implements ValidationRule
{
    protected array $messages = [];

    public function __construct(
        protected Client $turnstile,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = $this->turnstile->siteverify($value);

        if ($response->success) {
            return;
        }

        foreach ($response->errorCodes as $errorCode) {
            $fail(match ($errorCode) {
                'missing-input-secret' => __('cloudflare-turnstile::errors.missing-input-secret'),
                'invalid-input-secret' => __('cloudflare-turnstile::errors.invalid-input-secret'),
                'missing-input-response' => __('cloudflare-turnstile::errors.missing-input-response'),
                'invalid-input-response' => __('cloudflare-turnstile::errors.invalid-input-response'),
                'bad-request' => __('cloudflare-turnstile::errors.bad-request'),
                'timeout-or-duplicate' => __('cloudflare-turnstile::errors.timeout-or-duplicate'),
                'internal-error' => __('cloudflare-turnstile::errors.internal-error'),
                default => __('cloudflare-turnstile::errors.unexpected'),
            });
        }
    }
}
