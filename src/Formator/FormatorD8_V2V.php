<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\Ock\Util\UtilBase;
use Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface;

/**
 * Formator for decorator formulas that don't affect config form and summary.
 */
final class FormatorD8_V2V extends UtilBase {

  /**
   * @param \Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(
    Formula_ValueToValueBaseInterface $formula,
    UniversalAdapterInterface $adapter,
  ): FormatorD8Interface {
    return FormatorD8::fromFormula(
      $formula->getDecorated(),
      $adapter,
    );
  }

  /**
   * @param \Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *
   * @return \Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function createOptionable(
    Formula_ValueToValueBaseInterface $formula,
    UniversalAdapterInterface $adapter,
  ): ?OptionableFormatorD8Interface {
    return $adapter->adapt(
      $formula->getDecorated(),
      OptionableFormatorD8Interface::class,
    );
  }

}
