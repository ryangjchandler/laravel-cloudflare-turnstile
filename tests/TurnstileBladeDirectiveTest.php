<?php

use Illuminate\Support\Facades\Blade;
use function Spatie\Snapshots\assertMatchesHtmlSnapshot;

beforeEach(function () {
    config()->set('services.turnstile.key', '1x00000000000000000000AA');
    config()->set('services.turnstile.secret', '2x0000000000000000000000000000000AA');
});

it('can render a turnstile widget', function () {
    $html = Blade::render('@turnstile');

    assertMatchesHtmlSnapshot($html);
});

it('can render a turnstile widget with a custom action', function () {
    $html = Blade::render("@turnstile(['action' => 'test-action'])");

    assertMatchesHtmlSnapshot($html);
});

it('can render a turnstile widget with a custom cdata', function () {
    $html = Blade::render("@turnstile(['cdata' => 'test-cdata'])");

    assertMatchesHtmlSnapshot($html);
});

it('can render a turnstile widget with a custom callback', function () {
    $html = Blade::render("@turnstile(['callback' => 'testCallback'])");

    assertMatchesHtmlSnapshot($html);
});

it('can render a turnstile widget with a custom expired callback', function () {
    $html = Blade::render("@turnstile(['expired-callback' => 'testExpiredCallback'])");

    assertMatchesHtmlSnapshot($html);
});

it('can render a turnstile widget with a custom error callback', function () {
    $html = Blade::render("@turnstile(['error-callback' => 'testErrorCallback'])");

    assertMatchesHtmlSnapshot($html);
});

it('can render a turnstile widget with a custom theme', function () {
    $html = Blade::render("@turnstile(['theme' => 'dark'])");

    assertMatchesHtmlSnapshot($html);
});

it('can render a turnstile widget with a tabindex', function () {
    $html = Blade::render("@turnstile(['tabindex' => 1])");

    assertMatchesHtmlSnapshot($html);
});
