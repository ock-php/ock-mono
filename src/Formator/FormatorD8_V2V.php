<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\ObCK\Incarnator\IncarnatorInterface;
use Donquixote\ObCK\Util\UtilBase;

final class FormatorD8_V2V extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface|null
   *
   * @throws \Donquixote\ObCK\Exception\IncarnatorException
   */
  public static function create(
    Formula_ValueToValueBaseInterface $formula,
    IncarnatorInterface $incarnator
  ) {
    return FormatorD8::fromFormula(
      $formula->getDecorated(),
      $incarnator
    );
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\cu\Formator\Optionable\OptionableFormatorD8Interface|null
   *
   * @throws \Donquixote\ObCK\Exception\IncarnatorException
   */
  public static function createOptionable(
    Formula_ValueToValueBaseInterface $formula,
    IncarnatorInterface $incarnator
  ) {
    return FormatorD8::optionable(
      $formula->getDecorated(),
      $incarnator
    );
  }

}
