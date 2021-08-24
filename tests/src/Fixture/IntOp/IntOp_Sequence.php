<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Fixture\IntOp;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Text\Text;

class IntOp_Sequence implements IntOpInterface {

  /**
   * @var \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface[]
   */
  private $operations;

  /**
   * @obck("sequence", "Sequence")
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
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
   * @param \Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface[] $operations
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
