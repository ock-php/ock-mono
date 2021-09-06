<?php
declare(strict_types=1);

namespace Drupal\renderkit\Html;

/**
 * @see AttributesInterface
 */
trait HtmlAttributesTrait {

  /**
   * @var mixed[]
   */
  private $attributes = [];

  /**
   * @param array $attributes
   *
   * @return static
   */
  public function withAttributes(array $attributes): self {
    $clone = clone $this;
    $clone->attributes = $attributes;
    return $clone;
  }

  /**
   * @param string $key
   * @param mixed $value
   *
   * @return $this
   */
  public function setAttribute($key, $value): self {
    $this->attributes[$key] = $value;
    return $this;
  }

  /**
   * @param string $key
   * @param mixed $value
   *
   * @return static
   */
  public function withAttributeValue($key, $value): self {
    $clone = clone $this;
    $clone->attributes[$key] = $value;
    return $clone;
  }

  /**
   * @param string $class
   *
   * @return $this
   */
  public function addClass($class): self {
    $this->attributes['class'][] = $class;
    return $this;
  }

  /**
   * @param string $class
   *
   * @return static
   */
  public function withAddedClass($class): self {
    $clone = clone $this;
    $clone->attributes['class'][] = $class;
    return $clone;
  }

  /**
   * @param string[] $classes
   *
   * @return $this
   */
  public function addClasses(array $classes): self {
    foreach ($classes as $class) {
      $this->attributes['class'][] = $class;
    }
    return $this;
  }

  /**
   * @return mixed[]
   */
  protected function getAttributes(): array {
    return $this->attributes;
  }

  /**
   * @param string $tagName
   *
   * @return array
   */
  protected function tagNameBuildContainer($tagName): array {
    return [
      /* @see themekit_element_info() */
      /* @see theme_themekit_container() */
      '#theme_wrappers' => ['themekit_container'],
      '#tag_name' => $tagName,
      '#attributes' => $this->attributes,
    ];
  }

}
