<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer\Partial;

use Donquixote\ObCK\Cache\CacheInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Proxy\Cache\Formula_Proxy_CacheInterface;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_Proxy_Cache implements FormulaReplacerPartialInterface {

  /**
   * @var \Donquixote\ObCK\Cache\CacheInterface|null
   */
  private $cache;

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   */
  private $buffer = [];

  /**
   * @var true[]
   */
  private $seen = [];

  /**
   * @param \Donquixote\ObCK\Cache\CacheInterface|null $cache
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
  public function formulaGetReplacement(FormulaInterface $proxy, FormulaReplacerInterface $replacer): ?FormulaInterface {

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
