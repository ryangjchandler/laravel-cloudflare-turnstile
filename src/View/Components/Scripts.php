<?php

namespace RyanChandler\LaravelCloudflareTurnstile\View\Components;

use Illuminate\View\Component;

class Scripts extends Component
{
    public function render()
    {
        // @phpstan-ignore-next-line argument.type
        return view('cloudflare-turnstile::components.scripts');
    }
}
