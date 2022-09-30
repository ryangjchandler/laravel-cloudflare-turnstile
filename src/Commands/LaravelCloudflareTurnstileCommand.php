<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Commands;

use Illuminate\Console\Command;

class LaravelCloudflareTurnstileCommand extends Command
{
    public $signature = 'laravel-cloudflare-turnstile';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
