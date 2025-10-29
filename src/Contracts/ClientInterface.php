<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Contracts;

use RyanChandler\LaravelCloudflareTurnstile\Responses\SiteverifyResponse;

interface ClientInterface
{
    const RESPONSE_DUMMY_TOKEN = 'XXXX.DUMMY.TOKEN.XXXX';

    public function siteverify(string $response): SiteverifyResponse;
}
