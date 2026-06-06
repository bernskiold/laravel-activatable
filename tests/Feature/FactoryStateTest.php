<?php

use Bernskiold\LaravelActivatable\Tests\Models\Page;
use Bernskiold\LaravelActivatable\Tests\Models\Post;

it('creates an active model through the factory state', function () {
    $post = Post::factory()->active()->create();

    expect($post->isActive())->toBeTrue();
});

it('creates an inactive model through the factory state', function () {
    $post = Post::factory()->inactive()->create();

    expect($post->isActive())->toBeFalse();
});

it('sets the inactivated_at timestamp on an inactive factory state', function () {
    $page = Page::factory()->inactive()->create();

    expect($page->isActive())->toBeFalse()
        ->and($page->inactivated_at)->not->toBeNull();
});

it('leaves the inactivated_at timestamp null on an active factory state', function () {
    $page = Page::factory()->active()->create();

    expect($page->isActive())->toBeTrue()
        ->and($page->inactivated_at)->toBeNull();
});
