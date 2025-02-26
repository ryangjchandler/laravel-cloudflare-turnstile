@props([
    'id' => 'captcha',
])

@php
if (! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $id)) {
    throw new InvalidArgumentException("The Turnstile ID [{$id}] must start start with a letter or underscore, and can only contain alphanumeric or underscore characters.");
}
@endphp

<div
    class="cf-turnstile"
    data-sitekey="{{ config('services.turnstile.key') }}"
    @if ($attributes->has('wire:model'))
        wire:ignore
        data-callback="{{ $id }}Callback"
        data-expired-callback="{{ $id }}ExpiredCallback"
        data-timeout-callback="{{ $id }}ExpiredCallback"
        {{ $attributes->filter(fn($value, $key) => ! in_array($key, ['data-callback', 'data-expired-callback', 'data-timeout-callback', 'wire:model', 'id'])) }}
    @else
        {{ $attributes->whereStartsWith('data-') }}
    @endif
></div>

@if ($attributes->has('wire:model'))
    <script>
        function {{ $id }}Callback(token) {
            @this.set("{{ $attributes->get('wire:model') }}", token);
        }

        function {{ $id }}ExpiredCallback() {
            window.turnstile.reset();
        }
    </script>
@endif
