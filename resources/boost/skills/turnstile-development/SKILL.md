---
name: turnstile-development
description: Protect forms with Cloudflare Turnstile using ryangjchandler/laravel-cloudflare-turnstile — the scripts and widget Blade components, the validation rule, Livewire binding, translations, and faking the challenge in tests.
---

# Turnstile Development

## When to use this skill

Use this skill when the application has `ryangjchandler/laravel-cloudflare-turnstile`
installed and the task involves keeping bots out of a form: contact forms,
registration, newsletter sign-ups, comment boxes, password reset — anything a
visitor can submit without being logged in.

## How it works

Cloudflare's script renders a widget in the browser. When the visitor clears the
challenge, the widget writes a token into a hidden `cf-turnstile-response` input
inside its own container. That token is submitted with the form, and the server
exchanges it with Cloudflare's `siteverify` endpoint. A token is valid **once**
and expires after roughly five minutes.

There is no config file to publish. The package reads two values straight from
`config/services.php`:

```php
'turnstile' => [
    'key' => env('TURNSTILE_SITE_KEY'),      // public, rendered into the page
    'secret' => env('TURNSTILE_SECRET_KEY'), // private, used server-side only
],
```

Cloudflare publishes [test keys](https://developers.cloudflare.com/turnstile/troubleshooting/testing/)
that always pass or always fail — use those for local development rather than
disabling the widget.

## Rules

- **Never verify a token twice.** `siteverify` consumes it, so the second attempt
  comes back `timeout-or-duplicate`. Whenever a submit is rejected — for a
  Turnstile failure *or* for an unrelated field error — the visitor needs a fresh
  widget before they can retry.
- **The widget only renders on script load.** Cloudflare's `api.js` scans for
  `.cf-turnstile` when it loads and does not watch the DOM afterwards. A
  container that appears later (a Livewire morph, an `@if` flipping back, a modal
  opened after page load) stays blank until you call `window.turnstile.render()`
  yourself. Keep the widget mounted, or start over with a full page load.
- **Do not put the widget behind `wire:ignore` yourself when using
  `wire:model`** — the component already adds it, along with the callbacks it
  needs. A second `wire:ignore` is harmless; a competing `data-callback` is not.
- **The secret must never reach the page.** Only `services.turnstile.key` is
  rendered; nothing in this package outputs the secret.
- **Validate on the server, always.** The widget is a client-side gate. Without
  the rule it stops nobody.

## Setting it up

Load the script once, in the `<head>` of the layout:

```blade
<html>
    <head>
        <x-turnstile.scripts />
    </head>
```

The component passes its attributes onto the `<script>` tag, so a CSP nonce or an
`onload` handler goes there:

```blade
<x-turnstile.scripts nonce="{{ $nonce }}" />
```

Then drop the widget inside the form:

```blade
<form method="POST" action="/contact">
    @csrf

    <x-turnstile />

    <button>Submit</button>
</form>
```

And verify the token it submits:

```php
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

$request->validate([
    'cf-turnstile-response' => ['required', new Turnstile],
]);
```

## Widget options

Every [client-side rendering parameter](https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/#configuration-options)
passes straight through as an attribute:

```blade
<x-turnstile
    data-theme="light"      {{-- light | dark | auto (default) --}}
    data-language="nl"      {{-- widget copy; defaults to the visitor's browser --}}
    data-size="flexible"    {{-- normal | flexible | compact --}}
    data-appearance="interaction-only"
    data-action="contact"   {{-- shows up in Cloudflare's analytics --}}
    data-cdata="user-42"
    data-error-callback="onTurnstileError"
/>
```

`class` is merged, not replaced, so `class="mt-4"` keeps `cf-turnstile` intact.

## Livewire

Bind the token to a property with `wire:model`. The component then switches into
Livewire mode: it marks the container `wire:ignore` so morphs leave it alone,
registers `{id}Callback` / `{id}ExpiredCallback` globals, and emits a small script
that pushes the token into the property and resets the widget when the property is
cleared.

```blade
<x-turnstile id="contact_captcha" wire:model="turnstileToken" data-theme="light" />
```

- **The `id` must match `/^[a-zA-Z_][a-zA-Z0-9_]*$/`** — letters, digits and
  underscores only, never starting with a digit. It is not an HTML `id`; it names
  the callback globals. Give each widget on a page its own, or they overwrite each
  other's callbacks. Anything else throws `InvalidArgumentException` while
  rendering.
- **Clear the property on every rejected submit.** The emitted script watches the
  property and calls `window.turnstile.reset()` when it goes from filled to empty,
  which is what hands the visitor a usable widget again:

  ```php
  public string $turnstileToken = '';

  public function submit(): void
  {
      try {
          $validated = $this->validate([
              // 'bail' keeps a missing token from also being reported as a
              // rejected one, which would print two messages on one field.
              ...$this->getRules(), 'turnstileToken' => ['bail', 'required', new Turnstile],
          ]);
      } catch (ValidationException $e) {
          // Tokens are single-use; clearing the property resets the widget.
          $this->reset('turnstileToken');

          throw $e;
      }

      // ...
  }
  ```

  **Validate the challenge in the same pass as the rest of the form.** Checking
  it only after the other fields pass is tempting — it saves a round trip to
  Cloudflare when someone fats-fingers an e-mail address — but it means an empty
  form reports every field *except* the challenge, so the one control the visitor
  has no error next to is the one silently blocking them. `getRules()` returns the
  component's own rules, including those declared with `#[Validate]`, so merging
  is a one-liner.
- **A success screen that replaces the form cannot simply be toggled back.** The
  re-inserted container never renders (see the rules above), so send the visitor
  through a fresh request instead of `wire:click="$set('sent', false)"`:

  ```blade
  <a href="{{ route('contact') }}" class="btn">Nog een bericht sturen</a>
  ```

## Error messages

The rule reports Cloudflare's error codes through the
`cloudflare-turnstile::errors` translation namespace. Override them — to
translate, or to replace Cloudflare's wording with something a visitor can act
on — by publishing or hand-writing
`lang/vendor/cloudflare-turnstile/{locale}/errors.php`:

```bash
php artisan vendor:publish --tag=cloudflare-turnstile-translations
```

| Key | Means |
| --- | --- |
| `missing-input-secret`, `invalid-input-secret` | The app is misconfigured — the visitor can do nothing about it. |
| `missing-input-response` | No token was submitted; the visitor skipped the widget. |
| `invalid-input-response`, `timeout-or-duplicate` | The token expired or was already used. Ask them to try again. |
| `bad-request`, `internal-error`, `unexpected` | Transient; ask them to try again later. |

Views can be overridden the same way, with `--tag=cloudflare-turnstile-views`.

## Testing

Swap the client for a fake so tests never call Cloudflare:

```php
use RyanChandler\LaravelCloudflareTurnstile\Facades\Turnstile;

Turnstile::fake();             // every token passes
Turnstile::fake()->fail();     // invalid-input-response
Turnstile::fake()->expired();  // timeout-or-duplicate
```

`Turnstile::dummy()` returns a placeholder token to submit:

```php
$this->post('/contact', [
    'name' => 'Jan',
    'cf-turnstile-response' => Turnstile::dummy(),
])->assertRedirect();
```

Without a fake, the rule performs a real HTTP request — a test suite that forgets
one is slow, flaky, and dependent on the network. If a test asserts that the
challenge is *required* rather than that it *fails*, no fake is needed: the
`required` rule short-circuits before `siteverify` runs.

## Operational notes

- `Client::siteverify()` retries three times, 100ms apart. If Cloudflare is still
  unreachable, the rule lets the submission through rather than locking every
  visitor out of the form. Anything that must not fail open needs its own check
  on top.
- The client is registered as a **scoped** binding, so it resolves once per
  request and reads `services.turnstile.secret` at that moment. Tests that change
  the config must do so before anything resolves the client.
- Rate limiting and Turnstile solve different problems. A cleared challenge says
  "probably a human", not "not abusive" — keep the throttle on the route.
