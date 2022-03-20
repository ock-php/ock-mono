<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests\Fixture\IntOp;

use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Donquixote\Ock\Tests\Fixture\IntCondition\IntConditionInterface;

#[OckPluginInstance('conditional', 'Conditional')]
class IntOp_Conditional implements IntOpInterface {

  /**
   * @var \Donquixote\Ock\Tests\Fixture\IntCondition\IntConditionInterface
   */
  private $condition;

  /**
   * @var \Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface|null
   */
  private $opIfTrue;

  /**
   * @var \Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface|null
   */
  private $opIfFalse;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Tests\Fixture\IntCondition\IntConditionInterface $condition
   * @param \Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface|null $opIfTrue
   * @param \Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface|null $opIfFalse
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
