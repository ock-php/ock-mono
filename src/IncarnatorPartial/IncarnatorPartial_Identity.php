<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

#[OckIncarnator]
class IncarnatorPartial_Identity implements IncarnatorPartialInterface {

  /**
   * {@inheritdoc}
   */
  public function incarnate(FormulaInterface $formula, string $interface, IncarnatorInterface $incarnator): ?object {
    return ($formula instanceof $interface)
      ? $formula
      : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return \is_a(
      $resultInterface,
      FormulaInterface::class,
      TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $formulaClass): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return -100;
  }

}
