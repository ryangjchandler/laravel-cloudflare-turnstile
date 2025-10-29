<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Facades;

use Illuminate\Support\Facades\Facade;
use RyanChandler\LaravelCloudflareTurnstile\Client;

class Turnstile extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Client::class;
    }
}
