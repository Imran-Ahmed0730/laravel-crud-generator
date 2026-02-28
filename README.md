# Laravel CRUD Generator

[![Tests](https://github.com/yourvendor/laravel-crud-generator/actions/workflows/tests.yml/badge.svg)](https://github.com/yourvendor/laravel-crud-generator/actions/workflows/tests.yml)
[![Code Style](https://github.com/yourvendor/laravel-crud-generator/actions/workflows/lint.yml/badge.svg)](https://github.com/yourvendor/laravel-crud-generator/actions/workflows/lint.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/yourvendor/laravel-crud-generator.svg)](https://packagist.org/packages/yourvendor/laravel-crud-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/yourvendor/laravel-crud-generator.svg)](https://packagist.org/packages/yourvendor/laravel-crud-generator)
[![PHP Version](https://img.shields.io/packagist/php-v/yourvendor/laravel-crud-generator.svg)](https://packagist.org/packages/yourvendor/laravel-crud-generator)
[![License](https://img.shields.io/packagist/l/yourvendor/laravel-crud-generator.svg)](LICENSE)

A Laravel package that scaffolds a **complete, production-ready CRUD resource** from a single Artisan command — with clean, strictly-typed, well-commented code that is ready to extend without rewriting.

```bash
php artisan make:crud BlogPost
```

---

## What Gets Generated

```
app/
├── Http/
│   ├── Controllers/
│   │   └── BlogPostController.php        ← thin resource controller
│   └── Requests/
│       └── BlogPost/
│           ├── StoreBlogPostRequest.php   ← create validation
│           └── UpdateBlogPostRequest.php  ← update validation (with "sometimes")
├── Models/
│   └── BlogPost.php                      ← model with fillable + auto casts
├── Rules/
│   └── BlogPostRule.php                  ← custom validation rule scaffold
└── Services/
    └── BlogPostService.php               ← business logic, DB transactions, logging

database/
└── migrations/
    └── 2025_01_01_000000_create_blog_posts_table.php

resources/views/
└── blog-posts/
    ├── index.blade.php                   ← paginated table + search
    ├── show.blade.php                    ← read-only detail card
    ├── create.blade.php                  ← create form
    ├── edit.blade.php                    ← edit form
    └── _form.blade.php                   ← shared partial (combined mode)

routes/
└── blog_posts.php                        ← dedicated resource route file
```

---

## Requirements

| Dependency | Version |
|------------|---------|
| PHP        | >= 8.1  |
| Laravel    | 10 or 11 |

---

## Installation

```bash
composer require yourvendor/laravel-crud-generator --dev
```

The service provider is **auto-discovered** — no manual registration required.

### Publish config (optional)

```bash
php artisan vendor:publish --tag=crud-generator-config
```

### Publish stubs to customise generated code (optional)

```bash
php artisan vendor:publish --tag=crud-generator-stubs
```

Stubs are copied to `stubs/crud-generator/`. The package uses your local stubs automatically when present.

---

## Usage

### Interactive mode (recommended)

```bash
php artisan make:crud BlogPost
```

The command guides you through each option:

```
┌─────────────────────────────────────────┐
│      Laravel CRUD Generator              │
└─────────────────────────────────────────┘

📝  Define your fields...
     Field name: title      → string, required
     Field name: body       → text, required
     Field name:            → (done)

📄  Form style? Combined (shared _form.blade.php) / Separate
🎨  CSS framework? tailwind / bootstrap
🔗  Route prefix? (e.g. admin)
🔒  Middleware? (e.g. auth,verified)
🗑   Soft deletes? No
📦  Generate migration? Yes

  CREATED   Model & Migration   app/Models/BlogPost.php
  CREATED   Rule                app/Rules/BlogPostRule.php
  CREATED   Requests            app/Http/Requests/BlogPost/StoreBlogPostRequest.php
  CREATED   Requests            app/Http/Requests/BlogPost/UpdateBlogPostRequest.php
  CREATED   Service             app/Services/BlogPostService.php
  CREATED   Controller          app/Http/Controllers/BlogPostController.php
  CREATED   Views               resources/views/blog_posts/index.blade.php
  ...

  ✅  CRUD scaffold complete!
```

### Non-interactive / scripted mode

```bash
php artisan make:crud BlogPost \
  --fields="title:string,body:text,published:boolean:nullable,published_at:datetime:nullable" \
  --prefix=admin \
  --middleware=auth \
  --framework=tailwind \
  --force
```

### More examples

```bash
# Admin panel with Bootstrap
php artisan make:crud Product --prefix=admin --framework=bootstrap --middleware=auth,verified

# With soft deletes (answer Yes at the prompt)
php artisan make:crud Article

# Overwrite previously generated files
php artisan make:crud Post --force
```

---

## Field Types

| Type          | Migration column    | Auto-validation      | HTML input        |
|---------------|---------------------|----------------------|-------------------|
| `string`      | `string()`          | `string\|max:255`    | `text`            |
| `text`        | `text()`            | `string\|max:65535`  | `textarea`        |
| `integer`     | `integer()`         | `integer`            | `number`          |
| `bigInteger`  | `bigInteger()`      | `integer\|min:0`     | `number`          |
| `boolean`     | `boolean()`         | `boolean`            | `checkbox`        |
| `date`        | `date()`            | `date`               | `date`            |
| `datetime`    | `dateTime()`        | `date_format:…`      | `datetime-local`  |
| `decimal`     | `decimal(10, 2)`    | `numeric`            | `number step=any` |
| `float`       | `float()`           | `numeric`            | `number step=any` |
| `json`        | `json()`            | `array`              | `text`            |
| `enum`        | `string()` + TODO   | `string`             | `text`            |

> **Smart detection:** Fields named `*email*` get `email` validation; fields named `*url*` or `*website*` get `url` validation — regardless of their declared type.

---

## Architecture

### Controller
Receives HTTP → calls Service → returns View or Redirect. Uses `report($e)` so exceptions reach your handler without leaking stack traces to users.

### Service
All business logic here. Every write runs in `DB::transaction()`. Sort columns are whitelisted to prevent SQL injection. When soft deletes are on, gains `restore()`, `forceDelete()`, and `onlyTrashed()`.

### Requests
`Store` uses strict rules. `Update` prefixes every rule with `sometimes` for safe partial (PATCH) payloads. `attributes()` is pre-filled with human-readable labels.

### Model
`declare(strict_types=1)`, auto-filled `$fillable`, auto-inferred `$casts`, optional `SoftDeletes`.

### Views
`layouts.app` parent, flash messages, required-field asterisks, `novalidate`, `step="any"` on float inputs. Tailwind CSS v3 or Bootstrap 5.

### Routes
One `routes/{plural}.php` file per resource. Include in `routes/web.php`:
```php
require base_path('routes/blog_posts.php');
```

---

## Configuration

```php
// config/crud-generator.php
return [
    'namespace'        => 'App',
    'css_framework'    => 'tailwind',   // or 'bootstrap'
    'route_middleware' => ['web'],
    'per_page'         => 15,
    'paths' => [
        'controller' => 'app/Http/Controllers',
        'request'    => 'app/Http/Requests',
        'rule'       => 'app/Rules',
        'service'    => 'app/Services',
        'model'      => 'app/Models',
        'migration'  => 'database/migrations',
        'views'      => 'resources/views',
        'routes'     => 'routes',
    ],
];
```

---

## After Scaffolding — Checklist

- [ ] Run `php artisan migrate`
- [ ] Add to `routes/web.php`: `require base_path('routes/blog_posts.php');`
- [ ] Implement search in `BlogPostService::paginate()`
- [ ] Add domain logic in `BlogPostRule::validate()` if needed
- [ ] Update `authorize()` in both Request classes
- [ ] Create `resources/views/layouts/app.blade.php` if it doesn't exist

---

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md).

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## License

MIT — see [LICENSE](LICENSE).
