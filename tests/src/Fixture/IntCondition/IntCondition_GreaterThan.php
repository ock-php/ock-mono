<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests\Fixture\IntCondition;

use Donquixote\Ock\Attribute\Plugin\OckPluginFormula;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Primitive\Formula_Int;
use Donquixote\Ock\Text\Text;

/**
 * Compares the number to a defined operand.
 */
class IntCondition_GreaterThan implements IntConditionInterface {

  /**
   * @var int
   */
  private $operand;

  /**
   * @return self
   */
  #[OckPluginInstance('positive', 'Number is positive')]
  public static function positive(): self {
    return new self(0);
  }

  /**
   * @return self
   */
  #[OckPluginInstance('not_negative', 'Number is not negative')]
  public static function notNegative(): self {
    return new self(-1);
  }

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  #[OckPluginFormula(self::class, 'greater_than', 'Greater than')]
  public static function formula(): FormulaInterface {
    return Formula::group()
      ->add('operand', new Formula_Int(), Text::t('Operand'))
      ->construct(self::class);
  }

  /**
   * Constructor.
   *
   * @param int $operand
   */
  public function __construct(int $operand) {
    $this->operand = $operand;
  }

  /**
   * {@inheritdoc}
   */
  public function check(int $number): bool {
    return $number > $this->operand;
  }

}
