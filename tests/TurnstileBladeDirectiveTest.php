<?php

use Illuminate\Support\Facades\Blade;

use function Spatie\Snapshots\assertMatchesHtmlSnapshot;

beforeEach(function () {
    config()->set('services.turnstile.key', '1x00000000000000000000AA');
    config()->set('services.turnstile.secret', '2x0000000000000000000000000000000AA');
});

it('can render a turnstile widget', function () {
    $html = Blade::render('<x-turnstile />');

    assertMatchesHtmlSnapshot(trim($html));
});

it('can render a turnstile widget with a custom action', function () {
    $html = Blade::render('<x-turnstile data-action="test-action" />');

    assertMatchesHtmlSnapshot(trim($html));
});

it('can render a turnstile widget with a custom cdata', function () {
    $html = Blade::render('<x-turnstile data-cdata="test-cdata" />');

    assertMatchesHtmlSnapshot(trim($html));
});

it('can render a turnstile widget with a custom callback', function () {
    $html = Blade::render('<x-turnstile data-callback="testCallback" />');

    assertMatchesHtmlSnapshot(trim($html));
});

it('can render a turnstile widget with a custom expired callback', function () {
    $html = Blade::render('<x-turnstile data-expired-callback="testExpiredCallback" />');

    assertMatchesHtmlSnapshot(trim($html));
});

it('can render a turnstile widget with a custom error callback', function () {
    $html = Blade::render('<x-turnstile data-error-callback="testErrorCallback" />');

    assertMatchesHtmlSnapshot(trim($html));
});

it('can render a turnstile widget with a custom theme', function () {
    $html = Blade::render('<x-turnstile data-theme="dark" />');

    assertMatchesHtmlSnapshot(trim($html));
});

it('can render a turnstile widget with a tabindex', function () {
    $html = Blade::render('<x-turnstile data-tabindex="1" />');

    assertMatchesHtmlSnapshot(trim($html));
});

it('can render a turnstile widget with a wire model', function () {
    $html = Blade::render('<x-turnstile wire:model="captcha" />');

    assertMatchesHtmlSnapshot(trim($html));
});
