<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\IntOp;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Formula;
use Donquixote\OCUI\Text\Text;

class IntOp_Sequence implements IntOpInterface {

  /**
   * @var \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface[]
   */
  private $operations;

  /**
   * @ocui("sequence", "Sequence")
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
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
   * @param \Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface[] $operations
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
