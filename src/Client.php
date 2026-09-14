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
        try {
            $response = Http::retry(3, 100)
                ->asForm()
                ->acceptJson()
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => config('services.turnstile.secret'),
                    'response' => $response,
                ]);
        } catch (\Throwable) {
            // Connection failure / non-2xx after exhausting retries
            // (Http::retry() rethrows). Fail closed: a verification we
            // could not complete must not be treated as solved.
            // (Previously the unreachable path returned success(), which
            // silently *bypassed* Turnstile whenever siteverify was down.
            // If you require fail-open behaviour, override the
            // ClientInterface binding in your application.)
            return SiteverifyResponse::failure(['internal-error']);
        }

        if (! $response->ok()) {
            return SiteverifyResponse::failure(['internal-error']);
        }

        // The siteverify endpoint returns HTTP 200 for *every* token —
        // valid or not — and reports the outcome in the JSON body's
        // `success` field. Success must be derived from that field;
        // otherwise a 200 response always falls through to failure().
        if ($response->json('success') === true) {
            return SiteverifyResponse::success();
        }

        return SiteverifyResponse::failure((array) $response->json('error-codes'));
    }

    public function dummy(): string
    {
        return self::RESPONSE_DUMMY_TOKEN;
    }
}
