<?php

namespace Bernskiold\LaravelActivatable;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class LaravelActivatableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        AboutCommand::add('Laravel Activatable', fn () => ['Version' => '1.0.0']);

        $this->publishes([
            __DIR__.'/../config/activatable.php' => config_path('activatable.php'),
        ], 'activatable-config');

        $this->registerBlueprintMacros();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/activatable.php', 'activatable'
        );
    }

    protected function registerBlueprintMacros(): void
    {
        Blueprint::macro('activatable', function (?string $column = null): ColumnDefinition {
            /** @var Blueprint $this */
            $column ??= config('activatable.column', 'is_active');

            return $this->boolean($column)->default(config('activatable.default_active', true));
        });

        Blueprint::macro('inactivatedAt', function (?string $column = null): ColumnDefinition {
            /** @var Blueprint $this */
            $column ??= config('activatable.inactivated_at_column', 'inactivated_at');

            return $this->timestamp($column)->nullable();
        });

        Blueprint::macro('dropActivatable', function (?string $column = null): void {
            /** @var Blueprint $this */
            $column ??= config('activatable.column', 'is_active');

            $this->dropColumn($column);
        });
    }
}
