<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\IntOp;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Formula;
use Donquixote\OCUI\Formula\Primitive\Formula_Int;
use Donquixote\OCUI\Formula\Textfield\Formula_Textfield_IntegerInRange;
use Donquixote\OCUI\Text\Text;

class IntOp_Add implements IntOpInterface {

  /**
   * @var int
   */
  private $increment;

  /**
   * @ocui("add", "Add")
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
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
