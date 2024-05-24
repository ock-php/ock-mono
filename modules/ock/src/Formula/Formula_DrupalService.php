<?php

declare(strict_types=1);

namespace Drupal\ock\Formula;

use Ock\CodegenTools\Util\CodeGen;
use Ock\Ock\Formula\ValueProvider\Formula_FixedPhpInterface;

class Formula_DrupalService implements Formula_FixedPhpInterface {

  /**
   * Constructor.
   *
   * @param string $serviceId
   */
  public function __construct(
    private readonly string $serviceId,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getPhp(): string {
    // @todo Use an injected service?
    return CodeGen::phpCallStatic(
      [\Drupal::class, 'service'],
      [\var_export($this->serviceId, TRUE)],
    );
  }

}
