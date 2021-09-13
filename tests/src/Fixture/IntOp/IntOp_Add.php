<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests\Fixture\IntOp;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Primitive\Formula_Int;
use Donquixote\Ock\Text\Text;

class IntOp_Add implements IntOpInterface {

  /**
   * @var int
   */
  private $increment;

  /**
   * @obck("add", "Add")
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \ReflectionException
   */
  public static function formula(): FormulaInterface {
    return Formula::group()
      ->add('increment', new Formula_Int(), Text::t('Increment'))
      ->construct(self::class);
  }

  /**
   * Constructor.
   *
   * @param int $increment
   */
  public function __construct(int $increment) {
    $this->increment = $increment;
  }

  /**
   * {@inheritdoc}
   */
  public function transform(int $number): int {
    return $number + $this->increment;
  }

}
