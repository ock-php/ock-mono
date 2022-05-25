<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Proxy\Cache;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_Fixed;

abstract class Formula_Proxy_Cache_SelectBase implements Formula_Proxy_CacheInterface {

  /**
   * @param string $cacheId
   */
  public function __construct(
    private readonly string $cacheId,
  ) {}

  /**
   * @return string
   */
  final public function getCacheId(): string {
    return $this->cacheId;
  }

  /**
   * @param mixed $data
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  final public function dataGetFormula($data): FormulaInterface {
    return new Formula_Select_Fixed($data);
  }

  /**
   * {@inheritdoc}
   */
  final public function getData() {
    return $this->getGroupedOptions();
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  abstract protected function getGroupedOptions(): array;

}
