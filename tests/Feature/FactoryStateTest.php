<?php

use Bernskiold\LaravelActivatable\Tests\Models\Post;

it('creates an active model through the factory state', function () {
    $post = Post::factory()->active()->create();

    expect($post->isActive())->toBeTrue();
});

it('creates an inactive model through the factory state', function () {
    $post = Post::factory()->inactive()->create();

    expect($post->isActive())->toBeFalse();
});
