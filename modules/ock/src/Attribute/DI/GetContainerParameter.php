<?php

declare(strict_types = 1);

namespace Drupal\ock\Attribute\DI;

#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class GetContainerParameter {

  /**
   * Constructor.
   *
   * @param string $name
   */
  public function __construct(
    public readonly string $name,
  ) {}

}
