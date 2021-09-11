<?php

declare(strict_types=1);

namespace Drupal\cu\Formula;

use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProviderInterface;

class Formula_DrupalService implements Formula_ValueProviderInterface {

  private string $serviceId;

  /**
   * Constructor.
   *
   * @param string $serviceId
   */
  public function __construct(string $serviceId) {
    $this->serviceId = $serviceId;
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    return \Drupal::service($this->serviceId);
  }

  /**
   * {@inheritdoc}
   */
  public function getPhp(): string {
    return '\\' . \Drupal::class . '::service('
      . var_export($this->serviceId, TRUE)
      . ')';
  }

}
