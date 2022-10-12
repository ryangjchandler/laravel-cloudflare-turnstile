<div class="cf-turnstile"
     data-sitekey="{{ config('services.turnstile.key') }}"
     @foreach($configurations ?? [] as $key => $value) data-{{ $key }}="{{ $value }}" @endforeach
></div>
