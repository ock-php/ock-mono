<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Fixture\IntFormat;

use Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface;

/**
 * @obck(
 *   "intOpDecorator",
 *    "Integer operation decorator",
 *    decorator = true)
 */
class IntFormat_IntOpDecorator implements IntFormatInterface {

  /**
   * @var \Donquixote\ObCK\Tests\Fixture\IntFormat\IntFormatInterface|null
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface
   */
  private $intOp;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Tests\Fixture\IntFormat\IntFormatInterface|null $decorated
   * @param \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface $intOp
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
