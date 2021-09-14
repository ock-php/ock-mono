<?php

declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Util\UtilBase;

final class Incarnator extends UtilBase {

  /**
   * Incarnates multiple formulas.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface[] $itemFormulas
   * @param string $interface
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return object[]
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   *   One of the item formulas is not supported.
   */
  public static function getMultiple(array $itemFormulas, string $interface, IncarnatorInterface $incarnator): array {

    Formula::validateMultiple($itemFormulas);

    $itemObjects = [];
    foreach ($itemFormulas as $k => $itemFormula) {
      $itemObjects[$k] = self::getObject($itemFormula, $interface, $incarnator);
    }

    return $itemObjects;
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param string $interface
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return object
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function getObject(FormulaInterface $formula, string $interface, IncarnatorInterface $incarnator): object {

    $object = $incarnator->incarnate($formula, $interface, $incarnator);

    if (!$object instanceof $interface) {
      throw new IncarnatorException('Misbehaving STA.');
    }

    return $object;
  }

}
