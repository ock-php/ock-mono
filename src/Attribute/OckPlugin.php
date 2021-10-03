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
   * @var array
   */
  private array $args = [];

  /**
   * Constructor.
   *
   * @param string $id
   * @param string $label
   * @param bool $decorator
   */
  public function __construct(string $id, string $label, bool $inline = FALSE, bool $decorator = FALSE) {
    $this->id = $id;
    $this->label = $label;
    $this->args['decorator'] = $decorator;
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
