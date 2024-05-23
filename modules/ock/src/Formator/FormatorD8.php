<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\FormulaAdapter;
use Ock\Ock\Util\UtilBase;
use Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface;
use Drupal\ock\Ock;

final class FormatorD8 extends UtilBase {

  /**
   * @param \Ock\Ock\Core\Formula\FormulaInterface $formula
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface|null $adapter
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  public static function fromFormula(
    FormulaInterface $formula,
    UniversalAdapterInterface $adapter = NULL,
  ): FormatorD8Interface {
    return FormulaAdapter::requireObject(
      $formula,
      FormatorD8Interface::class,
      $adapter ?? Ock::adapter(),
    );
  }

  /**
   * @param \Ock\Ock\Core\Formula\FormulaInterface $formula
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface|null
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  public static function optional(
    FormulaInterface $formula,
    UniversalAdapterInterface $adapter
  ): ?FormatorD8Interface {
    return self::optionable($formula, $adapter)
      ?->getOptionalFormator();
  }

  /**
   * @param \Ock\Ock\Core\Formula\FormulaInterface $formula
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *
   * @return \Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface|null
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  public static function optionable(
    FormulaInterface $formula,
    UniversalAdapterInterface $adapter
  ): ?OptionableFormatorD8Interface {
    return $adapter->adapt($formula, OptionableFormatorD8Interface::class);
  }

}
