<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Responses;

use Illuminate\Contracts\Support\Arrayable;

class SiteverifyResponse implements Arrayable
{
    public function __construct(
        public readonly bool $success,
        public readonly array $errorCodes,
    ) {
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'error-codes' => $this->errorCodes,
        ];
    }
}
