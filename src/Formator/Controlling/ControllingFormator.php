<?php

declare(strict_types=1);

namespace Drupal\ock\Formator\Controlling;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Drupal\ock\Ock;

class ControllingFormator {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface|null $adapter
   *
   * @return \Drupal\ock\Formator\Controlling\ControllingFormatorInterface
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public static function fromFormula(
    FormulaInterface $formula,
    UniversalAdapterInterface $adapter = NULL,
  ): ControllingFormatorInterface {
    return ($adapter ?? Ock::adapter())
      ->adapt($formula, ControllingFormatorInterface::class);
  }

}
