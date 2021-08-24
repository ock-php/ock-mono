<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Fixture\IntOp;

/**
 * Decorator which applies an additional operation.
 *
 * @obck(
 *   "extraOpDecorator",
 *   "Decorator with additional operation",
 *   decorator = true)
 */
class IntOp_ExtraOpDecorator implements IntOpInterface {

  /**
   * @var \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface
   */
  private $extraOp;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface $decorated
   *   Decorated operation.
   * @param \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface $extraOp
   *   Additional operation to run afterwards.
   */
  public function __construct(IntOpInterface $decorated, IntOpInterface $extraOp) {
    $this->decorated = $decorated;
    $this->extraOp = $extraOp;
  }

  /**
   * {@inheritdoc}
   */
  public function transform(int $number): int {
    $result = $this->decorated->transform($number);
    return $this->extraOp->transform($result);
  }

}
