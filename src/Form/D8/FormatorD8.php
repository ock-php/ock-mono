<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Form\D8\Optionable\OptionableFormatorD8Interface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\StaUtil;
use Donquixote\OCUI\Util\UtilBase;

final class FormatorD8 extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Form\D8\FormatorD8Interface|null
   */
  public static function fromFormula(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?FormatorD8Interface {

    $candidate = $formulaToAnything->formula(
      $formula,
      FormatorD8Interface::class);

    if (!$candidate instanceof FormatorD8Interface) {
      return NULL;
    }

    return $candidate;
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Form\D8\FormatorD8Interface|null
   */
  public static function optional(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?FormatorD8Interface {

    $optionable = self::optionable(
      $formula,
      $formulaToAnything
    );

    if (NULL === $optionable) {
      # kdpm('Sorry.', __METHOD__);
      return NULL;
    }

    return $optionable->getOptionalFormator();
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Form\D8\Optionable\OptionableFormatorD8Interface|null
   */
  public static function optionable(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?OptionableFormatorD8Interface {
    return StaUtil::getObject($formula, $formulaToAnything, OptionableFormatorD8Interface::class);
  }

}
