<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests\Fixture\IntOp;

use Donquixote\Ock\Attribute\PluginModifier\OckPluginDecorator;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Decorator which applies an additional operation.
 */
#[OckPluginInstance("extraOpDecorator", "Decorator with additional operation")]
#[OckPluginDecorator]
class IntOp_ExtraOpDecorator implements IntOpInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface $decorated
   *   Decorated operation.
   * @param \Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface $extraOp
   *   Additional operation to run afterwards.
   */
  public function __construct(
    private IntOpInterface $decorated,
    private IntOpInterface $extraOp,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function transform(int $number): int {
    $result = $this->decorated->transform($number);
    return $this->extraOp->transform($result);
  }

}
