<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula\Misc\SelectByEt;

interface SelectByEtInterface {

  /**
   * @return string
   */
  public function getCacheId(): string;

  /**
   * @param string $entityTypeId
   * @param string[]|null $bundleNames
   *   Format: $[] = $bundleName, or NULL to not restrict by bundle.
   *   If an empty array is specified, only base fields will be returned.
   *
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel
   */
  public function etGetGroupedOptions(string $entityTypeId, array $bundleNames = NULL): array;
}
