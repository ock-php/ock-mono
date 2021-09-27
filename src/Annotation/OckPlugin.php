<?php

declare(strict_types=1);

namespace Donquixote\Ock\Annotation;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class OckPlugin {

  /**
   * @var string
   */
  private string $id;

  /**
   * @var string
   */
  private string $label;

  /**
   * @var mixed[]
   */
  private array $args;

  /**
   * Constructor.
   *
   * @param string $id
   * @param string $label
   * @param mixed ...$args
   *   Additional arguments.
   */
  public function __construct(string $id, string $label, ...$args) {
    $this->id = $id;
    $this->label = $label;
    $this->args = $args;
  }

  /**
   * @return string
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getLabel(): string {
    return $this->label;
  }

  /**
   * @return mixed[]
   */
  public function getArgs(): array {
    return $this->args;
  }

}
