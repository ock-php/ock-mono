<?php

declare(strict_types = 1);

namespace Drupal\ock\UI\Markup;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Component\Utility\Html;

class Markup_DefinitionList implements MarkupInterface {

  /**
   * @var (string|MarkupInterface)[]
   */
  private array $parts = [];

  /**
   * @param string|\Drupal\Component\Render\MarkupInterface $content
   *
   * @return $this
   */
  public function addDt(string|MarkupInterface $content): static {
    $this->parts[] = new FormattableMarkup('<dt>@content</dt>', ['@content' => $content]);
    return $this;
  }

  /**
   * @param string|\Drupal\Component\Render\MarkupInterface $content
   *
   * @return $this
   */
  public function addDd(string|MarkupInterface $content): static {
    $this->parts[] = new FormattableMarkup('<dd>@content</dd>', ['@content' => $content]);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    $markup = '';
    foreach ($this->parts as $part) {
      $markup .= static::placeholderEscape($part);
    }
    return sprintf('<dl>%s</dl>', $markup);
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
