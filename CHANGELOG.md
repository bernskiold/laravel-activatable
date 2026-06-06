# Changelog

All notable changes to `laravel-activatable` will be documented in this file.

## 1.0.0 - 2026-06-06

**Full Changelog**: https://github.com/bernskiold/laravel-activatable/commits/1.0.0

## Unreleased

- Initial release.
- `Activatable` model trait with `is_active` column, `active()` / `inactive()` scopes and `activate()` / `deactivate()` / `toggleActive()` helpers (idempotent — no write or event when the state is unchanged).
- Optional `inactivated_at` deactivation timestamp tracking.
- `ModelActivated` / `ModelDeactivated` events dispatched on state change.
- `ActivatableFactory` factory trait with `active()` / `inactive()` states.
- `activatable()`, `inactivatedAt()` and `dropActivatable()` schema blueprint macros.
