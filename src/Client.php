<?php

namespace RyanChandler\LaravelCloudflareTurnstile;

use Illuminate\Support\Facades\Http;
use RyanChandler\LaravelCloudflareTurnstile\Contracts\ClientInterface;
use RyanChandler\LaravelCloudflareTurnstile\Responses\SiteverifyResponse;

class Client implements ClientInterface
{
    public function __construct(
        protected string $secret,
    ) {}

    public function siteverify(string $response): SiteverifyResponse
    {
        $response = Http::retry(3, 100)
            ->asForm()
            ->acceptJson()
            ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => config('services.turnstile.secret'),
                'response' => $response,
            ]);

        if (! $response->ok()) {
            return SiteverifyResponse::success();
        }

        return SiteverifyResponse::failure($response->json('error-codes'));
    }

    public function dummy(): string
    {
        return self::RESPONSE_DUMMY_TOKEN;
    }
}
