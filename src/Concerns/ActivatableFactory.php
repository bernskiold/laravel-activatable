<?php

namespace Bernskiold\LaravelActivatable\Concerns;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Adds `active()` and `inactive()` states to an Eloquent factory. The states
 * resolve the active column (and the optional deactivation timestamp) from the
 * factory's model, so per-model overrides are respected.
 *
 * @mixin Factory
 */
trait ActivatableFactory
{
    public function active(): static
    {
        return $this->state(fn () => $this->activatableState(true));
    }

    public function inactive(): static
    {
        return $this->state(fn () => $this->activatableState(false));
    }

    /**
     * @return array<string, mixed>
     */
    protected function activatableState(bool $active): array
    {
        $model = $this->newModel();

        $state = [
            method_exists($model, 'getActiveColumn') ? $model->getActiveColumn() : config('activatable.column', 'is_active') => $active,
        ];

        if (method_exists($model, 'tracksInactivatedAt') && $model->tracksInactivatedAt()) {
            $state[$model->getInactivatedAtColumn()] = $active ? null : $model->freshTimestamp();
        }

        return $state;
    }
}
