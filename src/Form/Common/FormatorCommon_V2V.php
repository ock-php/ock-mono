<?php

declare(strict_types=1);

namespace Donquixote\Ock\Form\Common;

use Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\Ock\Incarnator\Incarnator;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class FormatorCommon_V2V {

  /**
   * @STA
   *
   * @param \Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param string $interface
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Form\Common\FormatorCommonInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(Formula_ValueToValueBaseInterface $formula, string $interface, IncarnatorInterface $incarnator): ?FormatorCommonInterface {

    if (!is_a($interface, FormatorCommonInterface::class, TRUE)) {
      return NULL;
    }

    /** @var \Donquixote\Ock\Form\Common\FormatorCommonInterface $candidate */
    $candidate = Incarnator::getObject(
      $formula->getDecorated(),
      $interface,
      $incarnator);

    return $candidate;
  }

}
