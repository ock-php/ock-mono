<?php

declare(strict_types=1);

namespace Drupal\ock;

use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Utility class to bridge Drupal translatable markup with Ock.
 *
 * @see \t()
 * @see \Donquixote\Ock\Text\Text
 */
class DrupalText {

  /**
   * @param mixed[] $sources
   * @param string[]|int[] $fails
   *
   * @return \Donquixote\Ock\Text\TextInterface[]
   */
  public static function multiple(array $sources, ?array &$fails = []): array {
    $texts = [];
    foreach ($sources as $delta => $source) {
      $text = self::fromVar($source);
      if ($text === NULL) {
        $text = Text::s($delta);
        $fails[$delta] = $delta;
      }
      $texts[$delta] = $text;
    }
    return $texts;
  }

  /**
   * @param mixed $source
   * @param string $else
   *
   * @return \Donquixote\Ock\Text\TextInterface
   */
  public static function fromVarOr($source, string $else): TextInterface {
    return self::fromVar($source) ?? Text::s($else);
  }

  /**
   * Attempts to cast an untrusted variable to Ock text.
   *
   * @param string|\Drupal\Core\StringTranslation\TranslatableMarkup|mixed $source
   *
   * @return \Donquixote\Ock\Text\TextInterface|null
   */
  public static function fromVar($source): ?TextInterface {
    if (is_string($source)) {
      return Text::s($source);
    }
    if ($source instanceof TextInterface) {
      return $source;
    }
    if ($source instanceof TranslatableMarkup) {
      return self::fromT($source);
    }
    return NULL;
  }

  /**
   * Produces a list, if there are multiple items.
   *
   * This is useful for plugin summaries.
   *
   * @param mixed|array $source
   *
   * @return \Donquixote\Ock\Text\TextInterface|null
   *   A text, or NULL if the source was empty or had an unexpected type.
   */
  public static function fromVarRecursiveUl($source): ?TextInterface {
    if (!is_array($source)) {
      return self::fromVar($source);
    }

    if (!$source) {
      return NULL;
    }

    if (count($source) === 1) {
      return self::fromVar(reset($source));
    }

    $parts = array_filter(
      array_map(
        [self::class, 'fromVarRecursiveUl'],
        $source));

    if (!$parts) {
      return NULL;
    }

    if (count($parts) === 1) {
      return reset($parts);
    }

    return Text::ul($parts);
  }

  /**
   * Converts Drupal translatable markup to Ock text.
   *
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup $source
   *
   * @return \Donquixote\Ock\Text\TextInterface
   */
  public static function fromT(TranslatableMarkup $source): TextInterface {
    return Text::t(
      $source->getUntranslatedString(),
      $source->getArguments());
  }

}
