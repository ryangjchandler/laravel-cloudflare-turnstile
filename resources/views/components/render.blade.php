<div class="cf-turnstile"
     data-sitekey="{{ config('services.turnstile.key') }}"
     @if($attributes->has('wire:model'))
         {{ $attributes->filter(fn ($value, $key) => ! in_array($key, ['data-callback', 'wire:model'])) }}
         data-callback="captchaCallback"
     @else
         {{ $attributes->whereStartsWith('data-') }}
     @endif
></div>
<script>
function captchaCallback(token) {
    console.log(token);
}
</script>
