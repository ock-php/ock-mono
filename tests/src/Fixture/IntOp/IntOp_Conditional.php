<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\IntOp;

use Donquixote\OCUI\Tests\Fixture\IntCondition\IntConditionInterface;

/**
 * @ocui("conditional", "Conditional")
 */
class IntOp_Conditional implements IntOpInterface {

  /**
   * @var \Donquixote\OCUI\Tests\Fixture\IntCondition\IntConditionInterface
   */
  private $condition;

  /**
   * @var \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface|null
   */
  private $opIfTrue;

  /**
   * @var \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface|null
   */
  private $opIfFalse;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Tests\Fixture\IntCondition\IntConditionInterface $condition
   * @param \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface|null $opIfTrue
   * @param \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface|null $opIfFalse
   */
  public function __construct(IntConditionInterface $condition, ?IntOpInterface $opIfTrue, ?IntOpInterface $opIfFalse) {
    $this->condition = $condition;
    $this->opIfTrue = $opIfTrue;
    $this->opIfFalse = $opIfFalse;
  }

  /**
   * {@inheritdoc}
   */
  public function transform(int $number): int {
    $op = $this->condition->check($number)
      ? $this->opIfTrue
      : $this->opIfFalse;
    return ($op !== NULL)
      ? $op->transform($number)
      : $number;
  }

}
