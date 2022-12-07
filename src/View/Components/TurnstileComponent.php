<?php

namespace RyanChandler\LaravelCloudflareTurnstile\View\Components;

use Illuminate\View\Component;

class TurnstileComponent extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return <<<'blade'
<div class="cf-turnstile"
     data-sitekey="{{ config('services.turnstile.key') }}"
     @if($attributes->has('wire:model'))
        wire:ignore
        data-callback="captchaCallback"
        {{ $attributes->filter(fn ($value, $key) => ! in_array($key, ['data-callback', 'wire:model'])) }}
     @else
         {{ $attributes->whereStartsWith('data-') }}
     @endif
></div>
@if($attributes->has('wire:model'))
    <script>
        function captchaCallback(token) {
            @this.set("{{ $attributes->get('wire:model') }}", token);
        }
    </script>
@endif
blade;
    }
}
