<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\FormulaBase\Formula_ConfPassthruInterface;
use Ock\Ock\Util\UtilBase;
use Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface;

/**
 * Formator for decorator formulas that don't affect config form and summary.
 */
final class FormatorD8_V2V extends UtilBase {

  /**
   * @param \Ock\Ock\FormulaBase\Formula_ConfPassthruInterface $formula
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(
    Formula_ConfPassthruInterface $formula,
    UniversalAdapterInterface $adapter,
  ): FormatorD8Interface {
    return FormatorD8::fromFormula(
      $formula->getDecorated(),
      $adapter,
    );
  }

  /**
   * @param \Ock\Ock\FormulaBase\Formula_ConfPassthruInterface $formula
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *
   * @return \Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface|null
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function createOptionable(
    Formula_ConfPassthruInterface $formula,
    UniversalAdapterInterface $adapter,
  ): ?OptionableFormatorD8Interface {
    return $adapter->adapt(
      $formula->getDecorated(),
      OptionableFormatorD8Interface::class,
    );
  }

}
