<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Rules;

use Illuminate\Contracts\Validation\Rule;
use RyanChandler\LaravelCloudflareTurnstile\TurnstileClient;

class Turnstile implements Rule
{
    protected array $messages = [];

    public function __construct(
        protected TurnstileClient $turnstile,
    ) {
    }

    public function passes($attribute, $value)
    {
        $response = $this->turnstile->siteverify($value);

        if ($response->success) {
            return true;
        }

        foreach ($response->errorCodes as $errorCode) {
            $this->messages[] = $this->mapErrorCodeToMessage($errorCode);
        }

        return false;
    }

    public function message()
    {
        return $this->messages;
    }

    protected function mapErrorCodeToMessage(string $code): string
    {
        return match ($code) {
            'missing-input-secret' => 'The secret parameter was not passed.',
            'invalid-input-secret' => 'The secret parameter was invalid or did not exist.',
            'missing-input-response' => 'The response parameter was not passed.',
            'invalid-input-response' => 'The response parameter is invalid or has expired.',
            'bad-request' => 'The request was rejected because it was malformed.',
            'timeout-or-duplicate' => 'The response parameter has already been validated before.',
            'internal-error' => 'An internal error happened while validating the response.',
            default => 'An unexpected error occurred.',
        };
    }
}
