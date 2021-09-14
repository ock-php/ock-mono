<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Util;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

final class StaUtil extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $itemFormulas
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   * @param string $interface
   *
   * @return mixed[]|null
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function getMultiple(array $itemFormulas, FormulaToAnythingInterface $formulaToAnything, string $interface): ?array {

    $itemObjects = [];
    foreach ($itemFormulas as $k => $itemFormula) {
      if (!$itemFormula instanceof FormulaInterface) {
        throw new \RuntimeException("Item formula at key $k must be instance of FormulaInterface.");
      }
      $itemCandidate = self::getObject($itemFormula, $formulaToAnything, $interface);
      if (NULL === $itemCandidate) {
        return NULL;
      }
      $itemObjects[$k] = $itemCandidate;
    }

    return $itemObjects;
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   * @param string $interface
   *
   * @return object|null
   */
  public static function getObject(FormulaInterface $formula, FormulaToAnythingInterface $formulaToAnything, string $interface): ?object {

    $object = $formulaToAnything->formula($formula, $interface);

    if (!$object instanceof $interface) {
      return NULL;
    }

    return $object;
  }

}
