@php
    $id = $attributes->get('id', 'turnstile');
@endphp

<div
    class="cf-turnstile"
    data-sitekey="{{ config('services.turnstile.key') }}"
    @if ($attributes->has('wire:model'))
        wire:ignore
        data-callback="{{ $id }}Callback"
        {{ $attributes->filter(fn($value, $key) => ! in_array($key, ['data-callback', 'wire:model', 'id'])) }}
    @else
        {{ $attributes->whereStartsWith('data-') }}
    @endif
></div>

@if ($attributes->has('wire:model'))
    <script>
        function {{ $id }}Callback(token) {
            @this.set("{{ $attributes->get('wire:model') }}", token);
        }
    </script>
@endif
