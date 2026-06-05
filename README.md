# Laravel Activatable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bernskiold/laravel-activatable.svg?style=flat-square)](https://packagist.org/packages/bernskiold/laravel-activatable)
[![Tests](https://img.shields.io/github/actions/workflow/status/bernskiold/laravel-activatable/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bernskiold/laravel-activatable/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/bernskiold/laravel-activatable.svg?style=flat-square)](https://packagist.org/packages/bernskiold/laravel-activatable)

Give Eloquent models a simple active/inactive state — with query scopes, fluent
helpers, factory states, an optional deactivation timestamp, lifecycle events
and a schema macro.

## Installation

```bash
composer require bernskiold/laravel-activatable
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag=activatable-config
```

## Schema

Use the `activatable()` blueprint macro to add the boolean column:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->activatable();        // adds `is_active` boolean, default true
    $table->inactivatedAt();      // optional: adds nullable `inactivated_at` timestamp
    $table->timestamps();
});
```

Both macros return the column definition, so you can keep chaining:

```php
$table->activatable()->comment('Whether the post is live.');
```

## Usage

Add the trait to your model:

```php
use Bernskiold\LaravelActivatable\Concerns\Activatable;

class Post extends Model
{
    use Activatable;
}
```

New models default to active. You then get:

```php
$post->isActive();      // bool
$post->isInactive();    // bool

$post->activate();      // sets active + dispatches ModelActivated (on change)
$post->deactivate();    // sets inactive + dispatches ModelDeactivated (on change)
$post->toggleActive();

$post->activateQuietly();   // no model events, no Activatable events
$post->deactivateQuietly();

Post::active()->get();      // only active
Post::inactive()->get();    // only inactive
Post::active(false)->get(); // inverted
```

### Deactivation timestamp

Opt a model into tracking *when* it was deactivated. The timestamp is set on
deactivation and cleared on activation:

```php
class Post extends Model
{
    use Activatable;

    protected bool $tracksInactivatedAt = true;
}
```

Add the column with `$table->inactivatedAt();`. You can enable this globally via
the `track_inactivated_at` config option instead.

### Events

`activate()` and `deactivate()` dispatch `ModelActivated` / `ModelDeactivated`
**only when the state actually changes**. Each event exposes the `$model`.

```php
use Bernskiold\LaravelActivatable\Events\ModelDeactivated;

Event::listen(ModelDeactivated::class, function (ModelDeactivated $event) {
    // $event->model
});
```

### Factory states

Add the factory trait for `active()` / `inactive()` states:

```php
use Bernskiold\LaravelActivatable\Concerns\ActivatableFactory;

class PostFactory extends Factory
{
    use ActivatableFactory;
}

Post::factory()->inactive()->create();
```

## Custom column name

Override the column globally in `config/activatable.php`, or per model:

```php
class Widget extends Model
{
    use Activatable;

    public const ACTIVE_COLUMN = 'is_enabled';
}
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
