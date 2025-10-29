<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Facades;

use Illuminate\Support\Facades\Facade;
use RyanChandler\LaravelCloudflareTurnstile\Contracts\ClientInterface;
use RyanChandler\LaravelCloudflareTurnstile\Testing\FakeClient;

class Turnstile extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ClientInterface::class;
    }

    public static function fake(): FakeClient
    {
        static::swap($fake = new FakeClient);

        return $fake;
    }
}
