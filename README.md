# Activate and deactivate Eloquent models, the easy way

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bernskiold/laravel-activatable.svg?style=flat-square)](https://packagist.org/packages/bernskiold/laravel-activatable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/bernskiold/laravel-activatable/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bernskiold/laravel-activatable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/bernskiold/laravel-activatable/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/bernskiold/laravel-activatable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bernskiold/laravel-activatable.svg?style=flat-square)](https://packagist.org/packages/bernskiold/laravel-activatable)

Almost every app has models that can be turned on and off — users, products, feature flags, integrations. This package gives those models a clean active/inactive state, so you stop re-writing the same `is_active` boolean, the same scopes, and the same factory states in every project.

```php
$product->activate();
$product->deactivate();

Product::active()->get();   // only the live ones
Product::inactive()->get(); // only the switched-off ones
```

Add one trait, add one column, and you're done. Everything else — scopes, events, factory states, an optional "when was this turned off?" timestamp — is there when you want it and out of the way when you don't.

## Why you'll like it

- **One trait, zero ceremony.** New records default to active, the column is cast to a boolean, and you get `activate()` / `deactivate()` / `toggleActive()` for free.
- **Readable scopes.** `Product::active()` and `Product::inactive()` read like English, and invert with a single argument.
- **Idempotent by design.** Calling `deactivate()` on an already-inactive model is a true no-op — no redundant write, no misleading event.
- **Events when it matters.** `ModelActivated` and `ModelDeactivated` fire only when the state actually changes, so listeners aren't spammed.
- **Optional deactivation timestamp.** Opt in to an `inactivated_at` column and the package keeps it in sync — set on deactivation, cleared on activation.
- **Tidy migrations and factories.** A `$table->activatable()` schema macro and `active()` / `inactive()` factory states keep your test and migration code expressive.

## Installation

You can install the package via Composer:

```bash
composer require bernskiold/laravel-activatable
```

If you'd like to change the column name, the default state, or enable the deactivation timestamp globally, publish the config:

```bash
php artisan vendor:publish --tag=activatable-config
```

## Schema

Add the boolean column with the `activatable()` blueprint macro:

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->activatable();   // adds an `is_active` boolean, default true
    $table->timestamps();
});
```

Both schema macros return the column definition, so you can keep chaining:

```php
$table->activatable()->comment('Whether the product is purchasable.');
```

## Usage

Add the trait to your model:

```php
use Bernskiold\LaravelActivatable\Concerns\Activatable;

class Product extends Model
{
    use Activatable;
}
```

New records are active by default. From there:

```php
$product->isActive();    // bool
$product->isInactive();  // bool

$product->activate();      // sets active, dispatches ModelActivated (on change)
$product->deactivate();    // sets inactive, dispatches ModelDeactivated (on change)
$product->toggleActive();  // flips it

Product::active()->get();      // only active
Product::inactive()->get();    // only inactive
Product::active(false)->get(); // inverted — same as inactive()
```

Calling `activate()` / `deactivate()` on a model that is already in the requested state does nothing at all — no query is run and no event is dispatched.

Need to change the state without firing events (and without touching the model's own `saving`/`saved` events)? Reach for the quiet variants:

```php
$product->activateQuietly();
$product->deactivateQuietly();
```

### Events

`activate()` and `deactivate()` dispatch events **only when the state actually changes**. Each event exposes the `$model` that changed:

```php
use Bernskiold\LaravelActivatable\Events\ModelDeactivated;

Event::listen(ModelDeactivated::class, function (ModelDeactivated $event) {
    // $event->model was just switched off
});
```

### Tracking when a model was deactivated

Sometimes "is it off?" isn't enough — you want to know *when* it was switched off. Opt a model into an `inactivated_at` timestamp and the package keeps it in sync: it's set on deactivation and cleared again on activation.

```php
class Product extends Model
{
    use Activatable;

    protected bool $tracksInactivatedAt = true;
}
```

Add the column with the `inactivatedAt()` macro (or enable it for every model via the `track_inactivated_at` config option):

```php
$table->activatable();
$table->inactivatedAt();   // adds a nullable `inactivated_at` timestamp
```

### Factory states

Add the factory trait for expressive `active()` / `inactive()` states. They respect the model's column (and the deactivation timestamp, if it tracks one):

```php
use Bernskiold\LaravelActivatable\Concerns\ActivatableFactory;

class ProductFactory extends Factory
{
    use ActivatableFactory;
}

Product::factory()->inactive()->create();
```

### A different column name

Override the column globally in `config/activatable.php`, or per model with a constant:

```php
class FeatureFlag extends Model
{
    use Activatable;

    public const ACTIVE_COLUMN = 'is_enabled';
}
```

### Type-hinting

The trait implements the `Bernskiold\LaravelActivatable\Contracts\Activatable` interface, so you can type-hint against any activatable model:

```php
use Bernskiold\LaravelActivatable\Contracts\Activatable;

function publish(Activatable $model): void
{
    $model->activate();
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Erik Bernskiöld](https://bernskiold.com)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.
