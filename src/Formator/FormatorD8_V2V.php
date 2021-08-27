<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Util\UtilBase;

final class FormatorD8_V2V extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_ValueToValueBaseInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ) {
    return FormatorD8::fromFormula(
      $formula->getDecorated(),
      $formulaToAnything
    );
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Drupal\cu\Formator\Optionable\OptionableFormatorD8Interface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function createOptionable(
    Formula_ValueToValueBaseInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ) {
    return FormatorD8::optionable(
      $formula->getDecorated(),
      $formulaToAnything
    );
  }

}
