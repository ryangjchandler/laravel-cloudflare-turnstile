<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RyanChandler\LaravelCloudflareTurnstile\LaravelCloudflareTurnstile
 */
class LaravelCloudflareTurnstile extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \RyanChandler\LaravelCloudflareTurnstile\LaravelCloudflareTurnstile::class;
    }
}
