<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests\Fixture\IntCondition;

use Donquixote\Ock\Annotation\OckPlugin;

/**
 * @ock("odd", "Number is odd")
 */
#[OckPlugin("odd", "Number is odd")]
class IntCondition_Odd implements IntConditionInterface {

  /**
   * {@inheritdoc}
   */
  public function check(int $number): bool {
    return ($number % 2) === 1;
  }

}
