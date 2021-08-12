<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\OCUI\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class FormatorD8_V2V extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
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
   * @param \Donquixote\OCUI\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Drupal\cu\Formator\Optionable\OptionableFormatorD8Interface|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
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
