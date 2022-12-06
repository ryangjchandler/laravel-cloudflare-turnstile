<div class="cf-turnstile"
     data-sitekey="{{ config('services.turnstile.key') }}"
     @if($attributes->has('wire:model'))
         data-callback="$wire.set(@js($attributes->wire('model')), value)"
        {{ $attributes->whereDoesntStartWith('data-callback') }}
     @else
         {{ $attributes->whereStartsWith('data-') }}
     @endif
></div>
