<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests\Fixture\IntOp;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Primitive\Formula_Int;
use Donquixote\Ock\Text\Text;

class IntOp_Multiply implements IntOpInterface {

  /**
   * @var int
   */
  private $factor;

  /**
   * @obck("multiply", "Multiply")
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function formula(): FormulaInterface {
    return Formula::group()
      ->add('factor', new Formula_Int(), Text::t('Factor'))
      ->construct(self::class);
  }

  /**
   * Constructor.
   *
   * @param int $factor
   */
  public function __construct(int $factor) {
    $this->factor = $factor;
  }

  /**
   * {@inheritdoc}
   */
  public function transform(int $number): int {
    return $number * $this->factor;
  }

}
