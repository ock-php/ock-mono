<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Util;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Nursery\NurseryInterface;

final class StaUtil extends UtilBase {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface[] $itemFormulas
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   * @param string $interface
   *
   * @return mixed[]|null
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function getMultiple(array $itemFormulas, NurseryInterface $formulaToAnything, string $interface): ?array {

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
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   * @param string $interface
   *
   * @return object
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function getObject(FormulaInterface $formula, NurseryInterface $formulaToAnything, string $interface): object {

    $object = $formulaToAnything->breed($formula, $interface);

    if (!$object instanceof $interface) {
      throw new FormulaToAnythingException('Misbehaving STA.');
    }

    return $object;
  }

}
