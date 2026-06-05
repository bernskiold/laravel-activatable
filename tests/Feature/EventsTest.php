<?php

use Bernskiold\LaravelActivatable\Events\ModelActivated;
use Bernskiold\LaravelActivatable\Events\ModelDeactivated;
use Bernskiold\LaravelActivatable\Tests\Models\Post;
use Illuminate\Support\Facades\Event;

it('dispatches an event when a model is deactivated', function () {
    Event::fake([ModelActivated::class, ModelDeactivated::class]);

    $post = Post::create(['title' => 'Hello']);
    $post->deactivate();

    Event::assertDispatched(ModelDeactivated::class, fn (ModelDeactivated $event) => $event->model->is($post));
    Event::assertNotDispatched(ModelActivated::class);
});

it('dispatches an event when a model is activated', function () {
    $post = Post::create(['title' => 'Hello', 'is_active' => false]);

    Event::fake([ModelActivated::class, ModelDeactivated::class]);

    $post->activate();

    Event::assertDispatched(ModelActivated::class, fn (ModelActivated $event) => $event->model->is($post));
    Event::assertNotDispatched(ModelDeactivated::class);
});

it('does not dispatch an event when the state does not change', function () {
    $post = Post::create(['title' => 'Hello']);

    Event::fake([ModelActivated::class, ModelDeactivated::class]);

    $post->activate();

    Event::assertNotDispatched(ModelActivated::class);
    Event::assertNotDispatched(ModelDeactivated::class);
});

it('does not dispatch events when changing state quietly', function () {
    $post = Post::create(['title' => 'Hello']);

    Event::fake([ModelActivated::class, ModelDeactivated::class]);

    $post->deactivateQuietly();

    expect($post->fresh()->isActive())->toBeFalse();
    Event::assertNotDispatched(ModelDeactivated::class);
});
