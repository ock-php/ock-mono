<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\IntOp;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Formula;
use Donquixote\OCUI\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\OCUI\Formula\Primitive\Formula_Int;
use Donquixote\OCUI\Formula\Textfield\Formula_Textfield_IntegerInRange;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\Text_Translatable;

class IntOp_Multiply implements IntOpInterface {

  /**
   * @var int
   */
  private $factor;

  /**
   * @ocui("multiply", "Multiply")
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
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
