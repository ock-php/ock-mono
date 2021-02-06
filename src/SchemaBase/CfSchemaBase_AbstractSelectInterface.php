<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaBase;

/**
 * This is a base interface, which by itself does NOT extend CfSchemaInterface.
 */
interface CfSchemaBase_AbstractSelectInterface {

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions(): array;

  /**
   * @param string|int $id
   *
   * @return string|null
   */
  public function idGetLabel($id);

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown($id): bool;

}
