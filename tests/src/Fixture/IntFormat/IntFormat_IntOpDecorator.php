<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests\Fixture\IntFormat;

use Donquixote\Ock\Attribute\Plugin\OckPlugin;
use Donquixote\Ock\Attribute\PluginModifier\OckPluginDecorator;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;

#[OckPluginInstance('intOpDecorator', 'Integer operation decorator')]
#[OckPluginDecorator]
class IntFormat_IntOpDecorator implements IntFormatInterface {

  /**
   * @var \Donquixote\Ock\Tests\Fixture\IntFormat\IntFormatInterface|null
   */
  private $decorated;

  /**
   * @var \Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface
   */
  private $intOp;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Tests\Fixture\IntFormat\IntFormatInterface|null $decorated
   * @param \Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface $intOp
   */
  public function __construct(?IntFormatInterface $decorated, IntOpInterface $intOp) {
    $this->decorated = $decorated;
    $this->intOp = $intOp;
  }

  /**
   * {@inheritdoc}
   */
  public function format(int $number): string {
    $number = $this->intOp->transform($number);
    return $this->decorated->format($number);
  }

}
