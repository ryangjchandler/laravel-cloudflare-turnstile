<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Facades;

use Illuminate\Support\Facades\Facade;
use RyanChandler\LaravelCloudflareTurnstile\Contracts\ClientInterface;
use RyanChandler\LaravelCloudflareTurnstile\Testing\FakeClient;

/**
 * @method static string dummy()
 */
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
