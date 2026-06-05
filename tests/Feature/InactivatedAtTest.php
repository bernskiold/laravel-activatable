<?php

use Bernskiold\LaravelActivatable\Tests\Models\Page;
use Bernskiold\LaravelActivatable\Tests\Models\Post;
use Illuminate\Support\Carbon;

it('records the inactivated_at timestamp on deactivation', function () {
    $page = Page::create(['title' => 'Hello']);

    expect($page->inactivated_at)->toBeNull();

    $page->deactivate();

    expect($page->fresh()->inactivated_at)->toBeInstanceOf(Carbon::class);
});

it('clears the inactivated_at timestamp on activation', function () {
    $page = Page::create(['title' => 'Hello']);
    $page->deactivate();

    expect($page->fresh()->inactivated_at)->not->toBeNull();

    $page->activate();

    expect($page->fresh()->inactivated_at)->toBeNull();
});

it('reports whether a model tracks the inactivated_at timestamp', function () {
    expect((new Page)->tracksInactivatedAt())->toBeTrue()
        ->and((new Post)->tracksInactivatedAt())->toBeFalse();
});
