<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Cache\CacheInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Proxy\Cache\Formula_Proxy_CacheInterface;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_Proxy_Cache implements FormulaReplacerPartialInterface {

  /**
   * @var \Donquixote\OCUI\Cache\CacheInterface|null
   */
  private $cache;

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
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
  public function getSourceFormulaClass(): string {
    return Formula_Proxy_CacheInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(FormulaInterface $proxy, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$proxy instanceof Formula_Proxy_CacheInterface) {
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

    $replacement = $proxy->dataGetFormula($data);

    if (!$replacement instanceof FormulaInterface) {
      return NULL;
    }

    return $this->buffer[$cacheId] = $replacement;
  }
}
