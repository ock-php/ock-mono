<?php

declare(strict_types=1);

namespace Drupal\ock\Formula;

use Donquixote\Ock\Formula\ValueProvider\Formula_ValueProviderInterface;
use Donquixote\Ock\Util\PhpUtil;

class Formula_DrupalService implements Formula_ValueProviderInterface {

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
    return PhpUtil::phpCallStatic(
      [\Drupal::class, 'service'],
      [\var_export($this->serviceId, TRUE)],
    );
  }

}
