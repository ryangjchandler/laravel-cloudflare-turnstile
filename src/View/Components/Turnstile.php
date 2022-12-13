<?php

namespace RyanChandler\LaravelCloudflareTurnstile\View\Components;

use Illuminate\View\Component;

class Turnstile extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('cloudflare-turnstile::components.turnstile');
    }
}
