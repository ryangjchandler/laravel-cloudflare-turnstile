<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Rules;

use Illuminate\Contracts\Validation\Rule;
use RyanChandler\LaravelCloudflareTurnstile\TurnstileClient;

class Turnstile implements Rule
{
    protected array $messages = [];

    public function __construct(
        protected TurnstileClient $turnstile,
    ) {}

    public function passes($attribute, $value)
    {
        $response = $this->turnstile->siteverify($value);

        if ($response->success) {
            return true;
        }

        foreach ($response->errorCodes as $errorCode) {
            $this->messages[] = match ($errorCode) {
                'missing-input-secret' => __('cloudflare-turnstile::errors.missing-input-secret'),
                'invalid-input-secret' => __('cloudflare-turnstile::errors.invalid-input-secret'),
                'missing-input-response' => __('cloudflare-turnstile::errors.missing-input-response'),
                'invalid-input-response' => __('cloudflare-turnstile::errors.invalid-input-response'),
                'bad-request' => __('cloudflare-turnstile::errors.bad-request'),
                'timeout-or-duplicate' => __('cloudflare-turnstile::errors.timeout-or-duplicate'),
                'internal-error' => __('cloudflare-turnstile::errors.internal-error'),
                default => __('cloudflare-turnstile::errors.unexpected'),
            };
        }

        return false;
    }

    public function message()
    {
        return $this->messages;
    }
}
