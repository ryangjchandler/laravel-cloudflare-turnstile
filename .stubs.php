<?php

namespace Illuminate\Validation {

    use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

    class Rule {
        public static function turnstile(): Turnstile {}
    }
}
