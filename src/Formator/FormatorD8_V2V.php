<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\UtilBase;

final class FormatorD8_V2V extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
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
   * @param \Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
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
