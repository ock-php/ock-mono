<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Fixture\IntOp;

use Donquixote\ObCK\Tests\Fixture\IntCondition\IntConditionInterface;

/**
 * @obck("conditional", "Conditional")
 */
class IntOp_Conditional implements IntOpInterface {

  /**
   * @var \Donquixote\ObCK\Tests\Fixture\IntCondition\IntConditionInterface
   */
  private $condition;

  /**
   * @var \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface|null
   */
  private $opIfTrue;

  /**
   * @var \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface|null
   */
  private $opIfFalse;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Tests\Fixture\IntCondition\IntConditionInterface $condition
   * @param \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface|null $opIfTrue
   * @param \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface|null $opIfFalse
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
