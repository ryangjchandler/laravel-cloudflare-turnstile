<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Testing;

use Illuminate\Support\Testing\Fakes\Fake;
use RyanChandler\LaravelCloudflareTurnstile\Contracts\ClientInterface;
use RyanChandler\LaravelCloudflareTurnstile\Responses\SiteverifyResponse;

class FakeClient implements ClientInterface, Fake
{
    public function siteverify(string $response): SiteverifyResponse
    {
        return SiteverifyResponse::success();
    }
}
