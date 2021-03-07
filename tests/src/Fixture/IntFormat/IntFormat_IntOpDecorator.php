<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\IntFormat;

use Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface;

/**
 * @ocui(
 *   "intOpDecorator",
 *    "Integer operation decorator",
 *    decorator = true)
 */
class IntFormat_IntOpDecorator implements IntFormatInterface {

  /**
   * @var \Donquixote\OCUI\Tests\Fixture\IntFormat\IntFormatInterface|null
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface
   */
  private $intOp;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Tests\Fixture\IntFormat\IntFormatInterface|null $decorated
   * @param \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface $intOp
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
