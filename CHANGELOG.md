# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Added
- Nothing yet

### Changed
- Nothing yet

### Fixed
- Nothing yet

---

## [1.0.0] — 2025-01-01

### Added
- `make:crud {name}` Artisan command — full interactive CRUD scaffold
- **Model** generation with `$fillable`, `$casts`, optional `SoftDeletes`
- **Migration** generation with correct column types for all supported field types
- **Service** class with `paginate()`, `findById()`, `create()`, `update()`, `delete()` — all write operations wrapped in `DB::transaction()`
- **StoreRequest** and **UpdateRequest** with auto-generated validation rules; update rules automatically prefixed with `sometimes`
- **Custom Rule** class scaffold implementing `ValidationRule`
- **Resource Controller** — thin, delegates all logic to Service; uses `report()` for exception handling
- **Blade views** for `index`, `show`, `create`, `edit` — supports both **Tailwind CSS** and **Bootstrap 5**
- **Combined form** strategy — shared `_form.blade.php` partial included by create/edit
- **Separate form** strategy — standalone create and edit views
- **Route file** per resource with middleware and prefix support
- `FieldDefinition` value object — typed field representation with `castType()`, `htmlInputType()`, `updateValidation()`, `label()`
- `NameHelper` — centralises all name derivations (studly, camel, snake, kebab, plural forms)
- `GeneratorConfig` DTO — typed configuration, replaces raw arrays
- `ValidationGuesser` — infers sensible default rules from field name and type (email/URL detection by field name)
- Field name validation in interactive prompt (regex check for valid snake_case)
- `--fields` option for non-interactive / scripted usage
- `--prefix` option for route and view prefix (e.g. `admin`)
- `--middleware` option for route middleware
- `--framework` option to select CSS framework
- `--force` flag to overwrite existing files
- Published config support (`vendor:publish --tag=crud-generator-config`)
- Published stubs support (`vendor:publish --tag=crud-generator-stubs`) for user customisation
- `.gitattributes` excludes dev files from Composer dist installs
- PHPUnit test suite with Orchestra Testbench
- GitHub Actions CI matrix (PHP 8.1/8.2/8.3 × Laravel 10/11)
- GitHub Actions Pint linting workflow
- GitHub Actions release workflow (auto GitHub Release from version tags)
- `CONTRIBUTING.md` guide
- Issue and PR templates

[Unreleased]: https://github.com/yourvendor/laravel-crud-generator/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/yourvendor/laravel-crud-generator/releases/tag/v1.0.0
