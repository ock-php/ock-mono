<?php

declare(strict_types=1);

namespace Drupal\ock\Formator\Controlling;

use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\Core\Formula\FormulaInterface;

class ControllingFormator {

  /**
   * @param \Ock\Ock\Core\Formula\FormulaInterface $formula
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface|null $adapter
   *
   * @return \Drupal\ock\Formator\Controlling\ControllingFormatorInterface
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  public static function fromFormula(
    FormulaInterface $formula,
    UniversalAdapterInterface $adapter,
  ): ControllingFormatorInterface {
    return $adapter
      ->adapt($formula, ControllingFormatorInterface::class);
  }

}
