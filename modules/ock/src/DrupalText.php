<?php

declare(strict_types=1);

namespace Drupal\ock;

use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Utility class to bridge Drupal translatable markup with Ock.
 *
 * @see \t()
 * @see \Donquixote\Ock\Text\Text
 */
class DrupalText {

  /**
   * @param array[] $definitions
   *
   * @return \Donquixote\Ock\Text\TextInterface[]s
   */
  public static function fromArrays(array $definitions, string $key = 'label'): array {
    $texts = [];
    foreach ($definitions as $id => $definition) {
      $texts[$id] = self::fromVarOr($definitions[$key] ?? NULL, $id);
    }
    return $texts;
  }

  /**
   * @param string $string
   *   Original language-neutral text with placeholders.
   * @param (string|\Drupal\Component\Render\MarkupInterface|mixed)[] $replacements
   *   Replacements.
   *
   * @return \Donquixote\Ock\Text\TextInterface
   */
  public static function s(string $string, array $replacements): TextInterface {
    return Text::s($string, self::multiple($replacements));
  }

  /**
   * @param string $string
   * @param (string|\Drupal\Component\Render\MarkupInterface|mixed)[] $replacements
   *
   * @return \Donquixote\Ock\Text\TextInterface
   */
  public static function t(string $string, array $replacements): TextInterface {
    return Text::t($string, self::multiple($replacements));
  }

  /**
   * @param object[] $definitions
   * @param string $method
   *
   * @return \Donquixote\Ock\Text\TextInterface[]
   */
  public static function fromObjects(array $definitions, string $method = 'getLabel'): array {
    $texts = [];
    foreach ($definitions as $id => $definition) {
      $texts[$id] = self::fromVarOr($definitions->$method(), $id);
    }
    return $texts;
  }

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
  public static function fromVarOr(mixed $source, string $else): TextInterface {
    return self::fromVar($source) ?? Text::s($else);
  }

  /**
   * Attempts to cast an untrusted variable to Ock text.
   *
   * @param string|\Drupal\Core\StringTranslation\TranslatableMarkup|mixed $source
   *
   * @return \Donquixote\Ock\Text\TextInterface|null
   */
  public static function fromVar(mixed $source): ?TextInterface {
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
  public static function fromVarRecursiveUl(mixed $source): ?TextInterface {
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
      self::multiple($source->getArguments()),
    );
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return \Donquixote\Ock\Text\TextInterface
   */
  public static function fromEntity(EntityInterface $entity): TextInterface {
    return self::fromVarOr($entity->label(), $entity->id());
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface|null $entity
   *
   * @return \Donquixote\Ock\Text\TextInterface|null
   */
  public static function fromEntityOrNull(?EntityInterface $entity): ?TextInterface {
    return $entity ? self::fromEntity($entity) : NULL;
  }

}
