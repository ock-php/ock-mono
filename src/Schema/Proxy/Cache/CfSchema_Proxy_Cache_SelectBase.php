<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Proxy\Cache;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Select\CfSchema_Select_Fixed;

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
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
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
