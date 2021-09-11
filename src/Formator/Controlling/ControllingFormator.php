<?php

declare(strict_types=1);

namespace Drupal\cu\Formator\Controlling;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Incarnator\Incarnator;
use Donquixote\ObCK\Incarnator\IncarnatorInterface;

class ControllingFormator {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\cu\Formator\Controlling\ControllingFormatorInterface
   *
   * @throws \Donquixote\ObCK\Exception\IncarnatorException
   */
  public static function fromFormula(
    FormulaInterface $formula,
    IncarnatorInterface $incarnator
  ): ControllingFormatorInterface {

    /** @var \Drupal\cu\Formator\Controlling\ControllingFormatorInterface $candidate */
    $candidate = Incarnator::incarnate(
      $formula,
      ControllingFormatorInterface::class,
      $incarnator);

    return $candidate;
  }

}
