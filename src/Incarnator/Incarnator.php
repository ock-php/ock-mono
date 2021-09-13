<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Util\UtilBase;

final class Incarnator extends UtilBase {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface[] $itemFormulas
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $formulaToAnything
   * @param string $interface
   *
   * @return mixed[]|null
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function getMultiple(array $itemFormulas, IncarnatorInterface $formulaToAnything, string $interface): ?array {

    Formula::validateMultiple($itemFormulas);

    $itemObjects = [];
    foreach ($itemFormulas as $k => $itemFormula) {
      $itemCandidate = self::getObject($itemFormula, $formulaToAnything, $interface);
      if (NULL === $itemCandidate) {
        return NULL;
      }
      $itemObjects[$k] = $itemCandidate;
    }

    return $itemObjects;
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $formulaToAnything
   * @param string $interface
   *
   * @return object
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function getObject(FormulaInterface $formula, IncarnatorInterface $formulaToAnything, string $interface): object {

    $object = $formulaToAnything->breed($formula, $interface);

    if (!$object instanceof $interface) {
      throw new IncarnatorException('Misbehaving STA.');
    }

    return $object;
  }

}
