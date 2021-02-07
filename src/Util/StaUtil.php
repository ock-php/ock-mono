<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Util;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

final class StaUtil extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $itemFormulas
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   * @param string $interface
   *
   * @return mixed[]|null
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function getMultiple(array $itemFormulas, FormulaToAnythingInterface $schemaToAnything, string $interface): ?array {

    $itemObjects = [];
    foreach ($itemFormulas as $k => $itemFormula) {
      if (!$itemFormula instanceof FormulaInterface) {
        throw new \RuntimeException("Item schema at key $k must be instance of FormulaInterface.");
      }
      $itemCandidate = self::getObject($itemFormula, $schemaToAnything, $interface);
      if (NULL === $itemCandidate) {
        return NULL;
      }
      $itemObjects[$k] = $itemCandidate;
    }

    return $itemObjects;
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   * @param string $interface
   *
   * @return mixed|null
   */
  public static function getObject(FormulaInterface $schema, FormulaToAnythingInterface $schemaToAnything, string $interface) {

    $object = $schemaToAnything->schema($schema, $interface);

    if (NULL === $object || !$object instanceof $interface) {
      return NULL;
    }

    return $object;
  }

}
