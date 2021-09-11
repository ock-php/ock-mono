<?php

declare(strict_types=1);

namespace Drupal\ock\Formator\Controlling;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Incarnator\Incarnator;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class ControllingFormator {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\ock\Formator\Controlling\ControllingFormatorInterface
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromFormula(
    FormulaInterface $formula,
    IncarnatorInterface $incarnator
  ): ControllingFormatorInterface {

    /** @var \Drupal\ock\Formator\Controlling\ControllingFormatorInterface $candidate */
    $candidate = Incarnator::incarnate(
      $formula,
      ControllingFormatorInterface::class,
      $incarnator);

    return $candidate;
  }

}
