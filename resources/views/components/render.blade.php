<div class="cf-turnstile"
     data-sitekey="{{ config('services.turnstile.key') }}"
     @if($attributes->has('wire:model'))
         {{ $attributes->filter(fn ($value, $key) => ! in_array($key, ['data-callback', 'wire:model'])) }}
         data-callback="function(token) { $wire.set({{ $attributes->get('wire:model') }}, token); }"
     @else
         {{ $attributes->whereStartsWith('data-') }}
     @endif
></div>
