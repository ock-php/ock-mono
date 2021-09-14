<?php
declare(strict_types=1);

namespace Donquixote\Ock\Form\Common;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartialZeroBase;

/**
 * @STA
 */
class FormatorCommon_Contextual extends IncarnatorPartialZeroBase {

  /**
   * {@inheritdoc}
   */
  public function incarnate(
    FormulaInterface $formula,
    string $interface,
    IncarnatorInterface $incarnator
  ): ?object {

    if (!$formula instanceof Formula_ContextualInterface) {
      return NULL;
    }

    return $incarnator->incarnate($formula->getDecorated(), $interface);
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return is_a(
      $resultInterface ,
      FormatorCommonInterface::class,
      TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $formulaClass): bool {
    return is_a(
      $formulaClass,
      Formula_ContextualInterface::class,
      TRUE);
  }

}
