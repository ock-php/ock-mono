<?php
declare(strict_types=1);

namespace Donquixote\Ock\Util;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\FormulaToAnythingException;
use Donquixote\Ock\Nursery\NurseryInterface;

final class StaUtil extends UtilBase {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface[] $itemFormulas
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   * @param string $interface
   *
   * @return mixed[]|null
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
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
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   * @param string $interface
   *
   * @return object
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  public static function getObject(FormulaInterface $formula, NurseryInterface $formulaToAnything, string $interface): object {

    $object = $formulaToAnything->breed($formula, $interface);

    if (!$object instanceof $interface) {
      throw new FormulaToAnythingException('Misbehaving STA.');
    }

    return $object;
  }

}
