<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Contracts;

use RyanChandler\LaravelCloudflareTurnstile\Responses\SiteverifyResponse;

interface ClientInterface
{
    public function siteverify(string $response): SiteverifyResponse;
}
