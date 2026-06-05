<?php

namespace Bernskiold\LaravelActivatable\Concerns;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Adds `active()` and `inactive()` states to an Eloquent factory.
 *
 * @mixin Factory
 */
trait ActivatableFactory
{
    public function active(): static
    {
        return $this->state([
            $this->activatableColumn() => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state([
            $this->activatableColumn() => false,
        ]);
    }

    protected function activatableColumn(): string
    {
        return config('activatable.column', 'is_active');
    }
}
