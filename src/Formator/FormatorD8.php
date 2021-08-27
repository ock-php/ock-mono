<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Drupal\cu\Formator\Optionable\OptionableFormatorD8Interface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Util\MessageUtil;
use Donquixote\ObCK\Util\StaUtil;
use Donquixote\ObCK\Util\UtilBase;

final class FormatorD8 extends UtilBase {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   *   Cannot build this formator.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): FormatorD8Interface {

    $candidate = $formulaToAnything->formula(
      $formula,
      FormatorD8Interface::class);

    if ($candidate instanceof FormatorD8Interface) {
      return $candidate;
    }

    throw new \RuntimeException(strtr(
      'Misbehaving FTA for formula of class @formula_class: Expected @interface object, found @found.',
      [
        '@formula_class' => get_class($formula),
        '@interface' => FormatorD8Interface::class,
        '@found' => MessageUtil::formatValue($candidate),
      ]));
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface|null
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
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Drupal\cu\Formator\Optionable\OptionableFormatorD8Interface|null
   */
  public static function optionable(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?OptionableFormatorD8Interface {
    return StaUtil::getObject($formula, $formulaToAnything, OptionableFormatorD8Interface::class);
  }

}
