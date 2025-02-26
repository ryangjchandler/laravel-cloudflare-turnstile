<?php

namespace RyanChandler\LaravelCloudflareTurnstile;

use Illuminate\Support\Facades\Http;
use RyanChandler\LaravelCloudflareTurnstile\Responses\SiteverifyResponse;

class TurnstileClient
{
    const SITEKEY_ALWAYS_PASSES_VISIBLE = '1x00000000000000000000AA';

    const SITEKEY_ALWAYS_BLOCKS_VISIBLE = '2x00000000000000000000AB';

    const SITEKEY_FORCE_INTERACTIVE_VISIBLE = '3x00000000000000000000FF';

    const SITEKEY_ALWAYS_PASSES_INVISIBLE = '1x00000000000000000000BB';

    const SITEKEY_ALWAYS_BLOCKS_INVISIBLE = '2x00000000000000000000BB';

    const SECRET_KEY_ALWAYS_PASSES = '1x0000000000000000000000000000000AA';

    const SECRET_KEY_ALWAYS_FAILS = '2x0000000000000000000000000000000AA';

    const SECRET_KEY_TOKEN_SPENT = '3x0000000000000000000000000000000AA';

    const RESPONSE_DUMMY_TOKEN = 'XXXX.DUMMY.TOKEN.XXXX';

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
            return new SiteverifyResponse(success: false, errorCodes: []);
        }

        return new SiteverifyResponse(success: $response->json('success'), errorCodes: $response->json('error-codes'));
    }
}
