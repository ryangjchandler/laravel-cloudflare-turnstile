<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Testing;

use Illuminate\Support\Testing\Fakes\Fake;
use RyanChandler\LaravelCloudflareTurnstile\Contracts\ClientInterface;
use RyanChandler\LaravelCloudflareTurnstile\Responses\SiteverifyResponse;

class FakeClient implements ClientInterface, Fake
{
    protected bool $shouldPass = true;

    protected bool $isExpired = false;

    public function fail(): self
    {
        $this->shouldPass = false;

        return $this;
    }

    public function expired(): self
    {
        $this->isExpired = true;

        return $this;
    }

    public function pass(): self
    {
        $this->shouldPass = true;

        return $this;
    }

    public function siteverify(string $response): SiteverifyResponse
    {
        if ($this->isExpired) {
            return SiteverifyResponse::failure(['timeout-or-duplicate']);
        }

        if (! $this->shouldPass) {
            return SiteverifyResponse::failure(['invalid-input-response']);
        }

        return SiteverifyResponse::success();
    }

    public function dummy(): string
    {
        return self::RESPONSE_DUMMY_TOKEN;
    }
}
