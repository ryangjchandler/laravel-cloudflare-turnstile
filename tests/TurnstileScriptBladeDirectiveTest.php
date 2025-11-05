<?php

use Illuminate\Support\Facades\Blade;

use function Spatie\Snapshots\assertMatchesHtmlSnapshot;

it('can render a turnstile script component', function () {
    $html = Blade::render('<x-turnstile.scripts />');

    assertMatchesHtmlSnapshot(trim($html));
});
