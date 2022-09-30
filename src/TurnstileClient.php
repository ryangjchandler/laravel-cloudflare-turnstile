<?php

namespace RyanChandler\LaravelCloudflareTurnstile;

use Illuminate\Support\Facades\Http;
use RyanChandler\LaravelCloudflareTurnstile\Responses\SiteverifyResponse;

class TurnstileClient
{
    public function __construct(
        protected string $secret,
    ) {
    }

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
            return new SiteverifyResponse(success: false, errorCodes: []);
        }

        return new SiteverifyResponse(success: $response->json('success'), errorCodes: $response->json('error-codes'));
    }
}
