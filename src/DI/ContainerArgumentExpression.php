<?php

declare(strict_types=1);

namespace Drupal\ock\DI;

class ContainerArgumentExpression {

  public function __construct(
    private readonly string $op,
    private readonly array $value,
  ) {}

  /**
   * @return string
   */
  public function getOp(): string {
    return $this->op;
  }

  /**
   * @return array
   */
  public function getValue(): array {
    return $this->value;
  }

}
