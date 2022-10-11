<div class="cf-turnstile"
     data-sitekey="{{ config('services.turnstile.key') }}"
     @if(isset($configurations['action'])) data-action="{{ $configurations['action'] }}" @endif
     @if(isset($configurations['cdata'])) data-cdata="{{ $configurations['cdata'] }}" @endif
     @if(isset($configurations['callback'])) data-callback="{{ $configurations['callback'] }}" @endif
     @if(isset($configurations['expired-callback'])) data-expired-callback="{{ $configurations['expired-callback'] }}" @endif
     @if(isset($configurations['error-callback'])) data-error-callback="{{ $configurations['error-callback'] }}" @endif
     @if(isset($configurations['theme'])) data-theme="{{ $configurations['theme'] }}" @endif
     @if(isset($configurations['tabindex'])) data-tabindex="{{ $configurations['tabindex'] }}" @endif
></div>
