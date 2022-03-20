<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests\Fixture\IntOp;

use Donquixote\Ock\Attribute\Plugin\OckPluginFormula;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;

class IntOp_Sequence implements IntOpInterface {

  /**
   * @var \Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface[]
   */
  private $operations;

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  #[OckPluginFormula("sequence", "Sequence")]
  public static function formula(): FormulaInterface {
    return Formula::group()
      ->add(
        'operations',
        Formula::ifaceSequence(IntOpInterface::class),
        Text::t('Operations'))
      ->construct(self::class);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface[] $operations
   */
  public function __construct(array $operations) {
    $this->operations = $operations;
  }

  /**
   * {@inheritdoc}
   */
  public function transform(int $number): int {
    foreach ($this->operations as $operation) {
      $number = $operation->transform($number);
    }
    return $number;
  }

}
