@props([
    'id' => 'captcha',
])

@php
if (! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $id)) {
    throw new InvalidArgumentException("The Turnstile ID [{$id}] must start start with a letter or underscore, and can only contain alphanumeric or underscore characters.");
}

$model = $attributes->has('wire:model') ? $attributes->get('wire:model') : null;
@endphp

<div
    class="cf-turnstile"
    data-sitekey="{{ config('services.turnstile.key') }}"
    @if ($model)
        wire:ignore
        data-callback="{{ $id }}Callback"
        data-expired-callback="{{ $id }}ExpiredCallback"
        data-timeout-callback="{{ $id }}ExpiredCallback"
        {{ $attributes->filter(fn($value, $key) => ! in_array($key, ['data-callback', 'data-expired-callback', 'data-timeout-callback', 'wire:model', 'id'])) }}
    @else
        {{ $attributes->whereStartsWith('data-') }}
    @endif
></div>

@if ($model)
    <script>
        function {{ $id }}Callback(token) {
            @this.set("{{ $model }}", token);
        }

        function {{ $id }}ExpiredCallback() {
            window.turnstile.reset();
        }

        @this.watch("{{ $model }}", (value, old) => {
            // If there was a value, and now there isn't, reset the Turnstile.
            if (!! old && ! value) {
                window.turnstile.reset();
            }
        })
    </script>
@endif
