<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Proxy\Cache;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Formula\Select\CfSchema_Select_Fixed;

abstract class CfSchema_Proxy_Cache_SelectBase implements CfSchema_Proxy_CacheInterface {

  /**
   * @var string
   */
  private $cacheId;

  /**
   * @param string $cacheId
   */
  public function __construct(string $cacheId) {
    $this->cacheId = $cacheId;
  }

  /**
   * @return string
   */
  final public function getCacheId(): string {
    return $this->cacheId;
  }

  /**
   * @param mixed $data
   *
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  final public function dataGetSchema($data): CfSchemaInterface {
    return new CfSchema_Select_Fixed($data);
  }

  /**
   * @return mixed
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
