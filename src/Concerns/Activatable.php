<?php

namespace Bernskiold\LaravelActivatable\Concerns;

use Bernskiold\LaravelActivatable\Events\ModelActivated;
use Bernskiold\LaravelActivatable\Events\ModelDeactivated;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Gives an Eloquent model an active/inactive state.
 *
 * Adds an `is_active` boolean (configurable), `active()` / `inactive()` query
 * scopes, `activate()` / `deactivate()` / `toggleActive()` helpers, optional
 * deactivation-timestamp tracking, and dispatches {@see ModelActivated} /
 * {@see ModelDeactivated} events when the state actually changes.
 *
 * @mixin Model
 */
trait Activatable
{
    public function initializeActivatable(): void
    {
        $this->mergeCasts([
            $this->getActiveColumn() => 'boolean',
        ]);

        if (! array_key_exists($this->getActiveColumn(), $this->attributes)) {
            $this->attributes[$this->getActiveColumn()] = config('activatable.default_active', true);
        }

        if ($this->tracksInactivatedAt()) {
            $this->mergeCasts([
                $this->getInactivatedAtColumn() => 'datetime',
            ]);
        }
    }

    public function isActive(): bool
    {
        return (bool) $this->{$this->getActiveColumn()};
    }

    public function isInactive(): bool
    {
        return ! $this->isActive();
    }

    public function activate(): static
    {
        return $this->setActiveState(true);
    }

    public function deactivate(): static
    {
        return $this->setActiveState(false);
    }

    public function toggleActive(): static
    {
        return $this->setActiveState($this->isInactive());
    }

    public function activateQuietly(): static
    {
        return $this->setActiveState(true, quietly: true);
    }

    public function deactivateQuietly(): static
    {
        return $this->setActiveState(false, quietly: true);
    }

    protected function setActiveState(bool $active, bool $quietly = false): static
    {
        $column = $this->getActiveColumn();

        // Already persisted in the desired state with no pending change: no-op,
        // so we avoid a redundant write and a misleading event.
        if ($this->exists && ! $this->isDirty($column) && $this->isActive() === $active) {
            return $this;
        }

        $wasActive = $this->exists ? (bool) $this->getOriginal($column) : null;

        $this->{$column} = $active;

        if ($this->tracksInactivatedAt()) {
            $this->{$this->getInactivatedAtColumn()} = $active ? null : $this->freshTimestamp();
        }

        $quietly ? $this->saveQuietly() : $this->save();

        if (! $quietly && $wasActive !== $active) {
            event($active ? new ModelActivated($this) : new ModelDeactivated($this));
        }

        return $this;
    }

    #[Scope]
    protected function active(Builder $query, bool $active = true): Builder
    {
        return $query->where($query->qualifyColumn($this->getActiveColumn()), $active);
    }

    #[Scope]
    protected function inactive(Builder $query, bool $inactive = true): Builder
    {
        return $query->where($query->qualifyColumn($this->getActiveColumn()), ! $inactive);
    }

    public function getActiveColumn(): string
    {
        return defined(static::class.'::ACTIVE_COLUMN')
            ? static::ACTIVE_COLUMN
            : config('activatable.column', 'is_active');
    }

    public function getInactivatedAtColumn(): string
    {
        return defined(static::class.'::INACTIVATED_AT_COLUMN')
            ? static::INACTIVATED_AT_COLUMN
            : config('activatable.inactivated_at_column', 'inactivated_at');
    }

    public function tracksInactivatedAt(): bool
    {
        if (property_exists($this, 'tracksInactivatedAt')) {
            return $this->tracksInactivatedAt;
        }

        return (bool) config('activatable.track_inactivated_at', false);
    }
}
