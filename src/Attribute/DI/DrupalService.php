<?php

declare(strict_types = 1);

namespace Drupal\ock\Attribute\DI;

#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class DrupalService extends ServiceIdBase {

  /**
   * Constructor.
   *
   * @param string $name
   *   Name of the service, or NULL to use interface name.
   */
  public function __construct(
    private readonly string $name,
  ) {}

  /**
   * {@inheritdoc}
   */
  protected function paramGetServiceId(\ReflectionParameter $parameter): string {
    return $this->name;
  }

}
