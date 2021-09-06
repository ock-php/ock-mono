<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Form\Common;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\Nursery\Cradle\CradleZeroBase;

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
