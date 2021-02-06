<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Cache\CacheInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Schema\Proxy\Cache\CfSchema_Proxy_CacheInterface;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_Proxy_Cache implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\OCUI\Cache\CacheInterface|null
   */
  private $cache;

  /**
   * @var \Donquixote\OCUI\Core\Schema\CfSchemaInterface[]
   */
  private $buffer = [];

  /**
   * @var true[]
   */
  private $seen = [];

  /**
   * @param \Donquixote\OCUI\Cache\CacheInterface|null $cache
   */
  public function __construct(CacheInterface $cache = NULL) {
    $this->cache = $cache;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceSchemaClass(): string {
    return CfSchema_Proxy_CacheInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(CfSchemaInterface $proxy, SchemaReplacerInterface $replacer): ?CfSchemaInterface {

    if (!$proxy instanceof CfSchema_Proxy_CacheInterface) {
      return NULL;
    }

    $cacheId = $proxy->getCacheId();

    if (isset($this->buffer[$cacheId])) {
      return $this->buffer[$cacheId];
    }

    if (isset($this->seen[$cacheId])) {
      return NULL;
    }

    $this->seen[$cacheId] = TRUE;

    if (NULL === $this->cache) {
      $data = $proxy->getData();
    }
    elseif (!$this->cache->getInto($cacheId, $data)) {
      $data = $proxy->getData();

      $this->cache->set($cacheId, $data);
    }

    $replacement = $proxy->dataGetSchema($data);

    if (!$replacement instanceof CfSchemaInterface) {
      return NULL;
    }

    return $this->buffer[$cacheId] = $replacement;
  }
}
