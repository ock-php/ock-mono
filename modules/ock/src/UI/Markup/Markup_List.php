<?php

declare(strict_types = 1);

namespace Drupal\ock\UI\Markup;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Component\Utility\Html;

class Markup_List implements MarkupInterface {

  /**
   * @var string|null
   */
  private ?string $itemWrapperSprintf = NULL;

  /**
   * @var string|null
   */
  private ?string $listWrapperSprintf = NULL;

  /**
   * Constructor.
   *
   * @param array $parts
   * @param string $glue
   */
  public function __construct(
    private readonly array $parts,
    private readonly string $glue = '',
  ) {}

  /**
   * Immutable setter. Sets wrappers for list and items.
   *
   * @param string|null $listTag
   * @param string|null $itemTag
   *
   * @return static
   */
  public function withTags(?string $listTag, ?string $itemTag): static {
    $clone = clone $this;
    $clone->listWrapperSprintf = ($listTag === NULL) ? NULL : "<$listTag>%s</$listTag>";
    $clone->itemWrapperSprintf = ($itemTag === NULL) ? NULL : "<$itemTag>%s</$itemTag>";
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    $parts = [];
    foreach ($this->parts as $part) {
      if ($this->itemWrapperSprintf !== NULL) {
        $part = sprintf($this->itemWrapperSprintf, $part);
      }
      $parts[] = static::placeholderEscape($part);
    }
    if ($this->itemWrapperSprintf === NULL) {
      $parts = $this->parts;
    }
    else {
    }
    $markup = implode($this->glue, $parts);
    if ($this->listWrapperSprintf !== NULL) {
      $markup = sprintf($this->listWrapperSprintf, $markup);
    }
    return $markup;
  }

  /**
   * Returns a representation of the object for use in JSON serialization.
   *
   * @return string
   *   The safe string content.
   */
  public function jsonSerialize(): string {
    return $this->__toString();
  }

  /**
   * Escapes a placeholder replacement value if needed.
   *
   * @param \Drupal\Component\Render\MarkupInterface|string $value
   *   A placeholder replacement value.
   *
   * @return string
   *   The properly escaped replacement value.
   */
  protected static function placeholderEscape(MarkupInterface|string $value): string {
    return $value instanceof MarkupInterface
      ? (string) $value
      : Html::escape($value);
  }

}
