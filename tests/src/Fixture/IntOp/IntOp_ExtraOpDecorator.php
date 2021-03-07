<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\IntOp;

/**
 * Decorator which applies an additional operation.
 *
 * @ocui(
 *   "extraOpDecorator",
 *   "Decorator with additional operation",
 *   decorator = true)
 */
class IntOp_ExtraOpDecorator implements IntOpInterface {

  /**
   * @var \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface
   */
  private $extraOp;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface $decorated
   *   Decorated operation.
   * @param \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface $extraOp
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
