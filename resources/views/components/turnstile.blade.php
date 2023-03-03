<div
    class="cf-turnstile"
    data-sitekey="{{ config('services.turnstile.key') }}"
    @if ($attributes->has('wire:model'))
        wire:ignore
        data-callback="captchaCallback"
        data-expired-callback="captchaExpired"
        data-timeout-callback="captchaExpired"
        {{ $attributes->filter(fn($value, $key) => ! in_array($key, ['data-callback', 'data-expired-callback', 'data-timeout-callback', 'wire:model'])) }}
    @else
        {{ $attributes->whereStartsWith('data-') }}
    @endif
></div>

@if ($attributes->has('wire:model'))
    <script>
        function captchaCallback(token) {
            @this.set("{{ $attributes->get('wire:model') }}", token);
        }

        function captchaExpired() {
            window.turnstile.reset()
        }
    </script>
@endif
