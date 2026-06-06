<?php

use Bernskiold\LaravelActivatable\Tests\Models\Post;
use Bernskiold\LaravelActivatable\Tests\Models\Widget;
use Illuminate\Support\Facades\DB;

it('defaults new models to active', function () {
    $post = Post::create(['title' => 'Hello']);

    expect($post->is_active)->toBeTrue()
        ->and($post->isActive())->toBeTrue()
        ->and($post->isInactive())->toBeFalse();
});

it('respects an explicitly set inactive state on create', function () {
    $post = Post::create(['title' => 'Hello', 'is_active' => false]);

    expect($post->isActive())->toBeFalse();
});

it('casts the active column to a boolean', function () {
    $post = Post::create(['title' => 'Hello']);

    expect($post->fresh()->is_active)->toBeTrue()
        ->and($post->fresh()->is_active)->toBeBool();
});

it('activates and deactivates a model', function () {
    $post = Post::create(['title' => 'Hello']);

    $post->deactivate();
    expect($post->fresh()->isActive())->toBeFalse();

    $post->activate();
    expect($post->fresh()->isActive())->toBeTrue();
});

it('toggles the active state', function () {
    $post = Post::create(['title' => 'Hello']);

    expect($post->toggleActive()->isActive())->toBeFalse()
        ->and($post->toggleActive()->isActive())->toBeTrue();
});

it('returns the model instance from state changes for chaining', function () {
    $post = Post::create(['title' => 'Hello']);

    expect($post->deactivate())->toBeInstanceOf(Post::class)
        ->and($post->activate())->toBeInstanceOf(Post::class);
});

it('scopes to active and inactive models', function () {
    $active = Post::create(['title' => 'Active']);
    $inactive = Post::create(['title' => 'Inactive', 'is_active' => false]);

    expect(Post::active()->pluck('id')->all())->toBe([$active->id])
        ->and(Post::inactive()->pluck('id')->all())->toBe([$inactive->id]);
});

it('allows the active scope to be inverted with an argument', function () {
    $active = Post::create(['title' => 'Active']);
    $inactive = Post::create(['title' => 'Inactive', 'is_active' => false]);

    expect(Post::active(false)->pluck('id')->all())->toBe([$inactive->id])
        ->and(Post::inactive(false)->pluck('id')->all())->toBe([$active->id]);
});

it('supports a custom active column via constant', function () {
    $widget = Widget::create(['name' => 'Gadget']);

    expect($widget->getActiveColumn())->toBe('is_enabled')
        ->and($widget->isActive())->toBeTrue();

    $widget->deactivate();

    expect($widget->fresh()->is_enabled)->toBeFalse()
        ->and(Widget::active()->count())->toBe(0);
});

it('does not issue a write when the state is already as requested', function () {
    $post = Post::create(['title' => 'Hello']);

    DB::enableQueryLog();
    DB::flushQueryLog();

    $post->activate();

    expect(DB::getQueryLog())->toBeEmpty();
});

it('honours the configured default active value', function () {
    config()->set('activatable.default_active', false);

    $post = Post::create(['title' => 'Hello']);

    expect($post->isActive())->toBeFalse();
});
