<?php

use Illuminate\Support\Facades\Schema;

it('adds the activatable column through the schema macro', function () {
    expect(Schema::hasColumn('posts', 'is_active'))->toBeTrue();
});

it('adds the inactivated_at column through the schema macro', function () {
    expect(Schema::hasColumn('pages', 'inactivated_at'))->toBeTrue();
});

it('supports a custom column name in the schema macro', function () {
    expect(Schema::hasColumn('widgets', 'is_enabled'))->toBeTrue()
        ->and(Schema::hasColumn('widgets', 'is_active'))->toBeFalse();
});
