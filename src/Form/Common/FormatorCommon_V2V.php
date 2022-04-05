<?php

declare(strict_types=1);

namespace Donquixote\Ock\Form\Common;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\Ock\Incarnator\Incarnator;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class FormatorCommon_V2V {

  /**
   * @template T of \Donquixote\Ock\Form\Common\FormatorCommonInterface
   *
   * @param \Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param class-string<T> $interface
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return T|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  #[OckIncarnator]
  public static function create(Formula_ValueToValueBaseInterface $formula, string $interface, IncarnatorInterface $incarnator): ?FormatorCommonInterface {

    if (!is_a($interface, FormatorCommonInterface::class, TRUE)) {
      return NULL;
    }

    /** @var \Donquixote\Ock\Form\Common\FormatorCommonInterface $candidate */
    return Incarnator::getObject(
      $formula->getDecorated(),
      $interface,
      $incarnator);
  }

}
