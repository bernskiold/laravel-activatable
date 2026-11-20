# Changelog

All notable changes to `laravel-activatable` will be documented in this file.

## 1.0.1 - 2026-11-20

### What's Changed

* Bump dependabot/fetch-metadata from 2.5.0 to 3.1.0 by @dependabot[bot] in https://github.com/bernskiold/laravel-activatable/pull/1
* Add PHP 8.5 and 8.6 support and drop Laravel 11 by @ErikBernskiold in https://github.com/bernskiold/laravel-activatable/pull/3
* Make PHP 8.6 a required part of the test matrix by @ErikBernskiold in https://github.com/bernskiold/laravel-activatable/pull/4

### New Contributors

* @dependabot[bot] made their first contribution in https://github.com/bernskiold/laravel-activatable/pull/1
* @ErikBernskiold made their first contribution in https://github.com/bernskiold/laravel-activatable/pull/3

**Full Changelog**: https://github.com/bernskiold/laravel-activatable/compare/1.0.0...1.0.1

## 1.0.0 - 2026-06-06

**Full Changelog**: https://github.com/bernskiold/laravel-activatable/commits/1.0.0

## Unreleased

- Initial release.
- `Activatable` model trait with `is_active` column, `active()` / `inactive()` scopes and `activate()` / `deactivate()` / `toggleActive()` helpers (idempotent — no write or event when the state is unchanged).
- Optional `inactivated_at` deactivation timestamp tracking.
- `ModelActivated` / `ModelDeactivated` events dispatched on state change.
- `ActivatableFactory` factory trait with `active()` / `inactive()` states.
- `activatable()`, `inactivatedAt()` and `dropActivatable()` schema blueprint macros.
