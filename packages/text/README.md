# Ock Text (Interoperable translatable markup)

Framework-agnostic, immutable text objects for translation and rendering.

Ock Text provides light-weight immutable objects that represent translatable interface strings.

Different text classes exist for different types of composition.

These classes are generally light-weight, with no access to application-specific services, and are meant to be serializable.

The actual translation should happen through a framework-specific implementation of `TranslatorInterface`, typically as an adapter around the framework-native translation service.

The return value is meant to be a string with safe html - but this depends on the specific implementations and the source strings.

## Why use this package?

Many libraries and domain models need to return labels or messages without depending on Symfony, Drupal, Laravel, or any other framework.

Instead of returning raw strings, you can return a text object:

```php
use Ock\Text\Text;

$label = Text::t('Hello @name', [
  '@name' => Text::s($user->getName()),
]);

echo $label->translate($translator);
```

This keeps translation concerns separate from the actual business logic.

## Core ideas

### Text objects

Text objects are immutable values that describe how a string should be produced.

Examples:
- Plain text
- Translatable text
- Text with replacements
- Concatenated lists
- Formatted strings

### Translator adapters

Translation is delegated to an implementation of `TranslatorInterface`.

This keeps your application code free from framework-specific translation logic.

```php
namespace Ock\Text;

interface TextInterface {
  public function translate(TranslatorInterface $translator): string;
}

interface TranslatorInterface {
  public function lookup(string $source, ?string $context = null): string;
}
```

The `TextInterface` contract makes it easy to build adapters for:
- Symfony's Translator
- Drupal's string translation
- custom message catalogs
- external translation services

## Installation

```bash
composer require ock/text
```

## Quick start

```php
use Ock\Text\Text;
use Ock\Text\Translator\TranslatorInterface;

$translator = new class implements TranslatorInterface {
  public function lookup(string $source, ?string $context = null): string {
    return match ($source) {
      'Hello @name' => 'Hallo @name',
      'Save' => 'Speichern',
      default => $source,
    };
  }
};

$text = Text::t('Hello @name', [
  '@name' => Text::s('Jane'),
]);

echo $text->translate($translator);
// Outputs: Hallo Jane
```

## Non-translatable text

Use `Text::s()` for strings that should not be translated:

```php
$raw = Text::s('This is language-neutral text.');
echo $raw->translate($translator);
// Outputs: This is language-neutral text.
```

## Replacements

You can compose text with placeholder replacements:

```php
$text = Text::t('User @name has @count messages.', [
  '@name' => Text::s('Jane'),
  '@count' => Text::i(3),
]);

echo $text->translate($translator);
```

## Context-aware translations

Some systems support translation context to distinguish otherwise identical strings.

```php
$text = Text::t('Save', [
  // optional replacements
]);

// A specific adapter can decide how to apply context
$translated = $text->translate($translatorWithContext);
```

You can also model specific variants explicitly:

```php
$text = new \Ock\Text\TranslatableWithContext('Save', 'button');
```

## Lists and composed text

The package supports structured text composition:

```php
$list = Text::ul([
  Text::t('First item'),
  Text::t('Second item'),
  Text::t('Third item'),
]);

echo $list->translate($translator);
```

This can produce safe, composable output for rendered UI and page content.

## HTML safety

The library makes a clear distinction between:
- raw/untrusted text
- translatable text
- formatted output

The `TextInterface` contract is designed to preserve this separation and allow adapters to safely render output for their target environment.

## Why not just use Symfony or Drupal translation objects?

This package is intentionally smaller and more generic.

It is useful when:
- you want non-framework-specific translatable strings
- you want to pass text objects through application layers
- you want to keep translation logic out of domain objects
- you want to adapt to different translation systems later

It is not meant to replace a complete translation framework. It is a portable abstraction for reusable text values.

## Package philosophy

Ock Text is intentionally:
- immutable
- composable
- framework-agnostic
- small in scope
- easy to adapt

You can plug in:
- Symfony translators
- Drupal translation services
- custom catalogs
- custom locale-aware renderers

without changing the object model used by your application code.

## License

MIT

## Contributing

Contributions are welcome. If you add a new text type, translator adapter, or rendering pattern, please make sure it remains framework-agnostic and minimal in scope.

## Status

This package is designed to be useful as a standalone library, not only as part of the larger Ock ecosystem.
