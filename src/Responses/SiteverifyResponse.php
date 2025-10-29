<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Responses;

use Illuminate\Contracts\Support\Arrayable;

class SiteverifyResponse implements Arrayable
{
    public function __construct(
        public readonly bool $success,
        public readonly array $errorCodes,
    ) {}

    public static function success(): self
    {
        return new self(true, []);
    }

    public static function failure(array $errorCodes = []): self
    {
        return new self(false, $errorCodes);
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'error-codes' => $this->errorCodes,
        ];
    }
}
