<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Cache\CacheInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Proxy\Cache\CfSchema_Proxy_CacheInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_Proxy_Cache implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\Cf\Cache\CacheInterface|null
   */
  private $cache;

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface[]
   */
  private $buffer = [];

  /**
   * @var true[]
   */
  private $seen = [];

  /**
   * @param \Donquixote\Cf\Cache\CacheInterface|null $cache
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
    elseif (isset($this->seen[$cacheId])) {
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
