# plugin-generator

A small CLI tool that scaffolds a [Shopware 6](https://www.shopware.com/) plugin skeleton
from a single command — `composer.json`, the main plugin class, `services.xml`, and a
PHPUnit test stub — instead of copy-pasting boilerplate from an existing plugin every time.

Built with [Symfony Console](https://symfony.com/doc/current/components/console.html).

## Why

Every Shopware plugin starts with the same handful of files, in the same shape, following
the same naming conventions. Getting that shape exactly right by hand is easy to get subtly
wrong (namespace mismatches between `composer.json`'s `shopware-plugin-class` and the actual
class, missing `services.xml`, forgetting the test stub). This tool generates that structure
correctly, every time, in a few seconds.

## Install

```bash
composer install
```

This installs Symfony Console and the dev tools (PHPUnit, PHP_CodeSniffer, PHPStan) and
makes the `bin/plugin-generator` executable available.

## Usage

```bash
php bin/plugin-generator create MyCustomPlugin \
    --vendor=acme \
    --author="Your Name"
```

This generates:

```
MyCustomPlugin/
├── composer.json
├── src/
│   ├── MyCustomPlugin.php
│   └── Resources/
│       └── config/
│           └── services.xml
└── tests/
    └── MyCustomPluginTest.php
```

### Options

| Option               | Description                                      | Default   |
|----------------------|---------------------------------------------------|-----------|
| `--vendor`           | Composer vendor namespace                          | `acme`    |
| `--author`           | Plugin author, written into `composer.json`         | `Your Name` |
| `--with-storefront`  | Also scaffold a storefront SCSS stub                | on       |
| `--target-dir`       | Directory to generate the plugin in                 | `.`       |

Example with a storefront stub, generated elsewhere:

```bash
php bin/plugin-generator create ProductBadges \
    --vendor=acme \
    --author="Your Name" \
    --with-storefront \
    --target-dir=../plugins
```

## Development

Run the test suite, linter, and static analysis locally the same way CI does:

```bash
vendor/bin/phpunit
vendor/bin/phpcs
vendor/bin/phpstan analyse
```

Continuous integration runs all three on every push, across PHP 8.2 — see
[`.github/workflows/ci.yml`](.github/workflows/ci.yml).

## Design notes

- `PluginGenerator` has no framework dependency — it only does filesystem and string work,
  which keeps it trivial to unit test without booting a console application.
- `ScaffoldCommand` is a thin adapter: it validates input and delegates to `PluginGenerator`.
- Templates live as plain files under `templates/`, rendered with simple placeholder
  substitution (`{{PLUGIN_NAME}}`, `{{NAMESPACE}}`, etc.) rather than a templating engine,
  to keep the dependency footprint minimal for a tool this size.

## License

MIT — see [LICENSE](LICENSE).
