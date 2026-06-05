<?php

namespace Bernskiold\LaravelActivatable\Contracts;

/**
 * Implemented by the {@see \Bernskiold\LaravelActivatable\Concerns\Activatable}
 * trait. Type-hint against this contract when you need to accept any model that
 * can be activated or deactivated.
 */
interface Activatable
{
    public function isActive(): bool;

    public function isInactive(): bool;

    public function activate(): static;

    public function deactivate(): static;

    public function toggleActive(): static;

    public function getActiveColumn(): string;

    public function getInactivatedAtColumn(): string;

    public function tracksInactivatedAt(): bool;
}
