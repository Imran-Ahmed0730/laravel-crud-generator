# Contributing

Thank you for considering a contribution! Here's how to get started.

---

## Development Setup

### 1. Fork and clone

```bash
git clone https://github.com/YOUR_FORK/laravel-crud-generator.git
cd laravel-crud-generator
```

### 2. Install dependencies

```bash
composer install
```

### 3. Run the test suite

```bash
composer test
```

### 4. Check code style

```bash
composer lint          # check only
composer lint-fix      # auto-fix
```

---

## Workflow

1. Create a feature branch from `main`:
   ```bash
   git checkout -b feature/my-improvement
   ```

2. Write your code and tests.

3. Ensure all checks pass:
   ```bash
   composer test
   composer lint
   ```

4. Update `CHANGELOG.md` under `## [Unreleased]`.

5. Open a Pull Request — fill in the PR template.

---

## Guidelines

### Testing

- Every new feature must have tests in `tests/Unit/` or `tests/Feature/`.
- Feature tests use Orchestra Testbench to run commands in a real Laravel environment.
- Tests must clean up generated files in `tearDown()`.

### Code Style

- PSR-12 + Laravel Pint defaults (`pint.json`).
- `declare(strict_types=1)` at the top of every PHP file.
- All classes, properties, and non-obvious methods must have a docblock.

### Commits

Follow [Conventional Commits](https://www.conventionalcommits.org/):

```
feat: add enum field support
fix: correct nullable boolean migration default
docs: update field type table in README
test: add FieldDefinition::fromArray test
chore: bump orchestra/testbench to ^9.0
```

### Generated File Quality

When changing a generator, paste an example of the generated output in your PR description.
The generated files should:
- Use `declare(strict_types=1)`
- Have appropriate docblocks
- Be immediately usable without modification
- Follow Laravel conventions

---

## Releasing (maintainers)

1. Update `CHANGELOG.md` — move `[Unreleased]` items to a new `[x.y.z]` section.
2. Commit: `git commit -m "chore: release v1.2.0"`
3. Tag: `git tag v1.2.0`
4. Push: `git push origin main --tags`

The `release.yml` workflow creates the GitHub Release automatically.
Packagist picks up the new tag within minutes.
