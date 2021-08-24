<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Fixture\IntOp;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\ObCK\Formula\Primitive\Formula_Int;
use Donquixote\ObCK\Formula\Textfield\Formula_Textfield_IntegerInRange;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\Text_Translatable;

class IntOp_Multiply implements IntOpInterface {

  /**
   * @var int
   */
  private $factor;

  /**
   * @obck("multiply", "Multiply")
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
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
