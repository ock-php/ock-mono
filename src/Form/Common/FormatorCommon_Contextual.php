<?php
declare(strict_types=1);

namespace Donquixote\Ock\Form\Common;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\Ock\Nursery\Cradle\CradleZeroBase;
use Donquixote\Ock\Nursery\NurseryInterface;

/**
 * @STA
 */
class FormatorCommon_Contextual extends CradleZeroBase {

  /**
   * {@inheritdoc}
   */
  public function breed(
    FormulaInterface $formula,
    string $interface,
    NurseryInterface $nursery
  ): ?object {

    if (!$formula instanceof Formula_ContextualInterface) {
      return NULL;
    }

    return $nursery->breed($formula->getDecorated(), $interface);
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
