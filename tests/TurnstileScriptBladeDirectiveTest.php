<?php

use Illuminate\Support\Facades\Blade;

use function Spatie\Snapshots\assertMatchesHtmlSnapshot;

it('can render a turnstile script snippet', function () {
    $html = Blade::render('@turnstileScripts');

    assertMatchesHtmlSnapshot(trim($html));
});

it('can render a turnstile script component', function () {
    $html = Blade::render('<x-turnstile.scripts />');

    assertMatchesHtmlSnapshot(trim($html));
});
