<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Cache\CacheInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Cache\Formula_Cache_SelectInterface;
use Donquixote\OCUI\Formula\Select\Formula_Select_Fixed;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_Cache_Select implements FormulaReplacerPartialInterface {

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
    return Formula_Cache_SelectInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function formulaGetReplacement(FormulaInterface $proxy, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$proxy instanceof Formula_Cache_SelectInterface) {
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
      $groupedOptions = $this->buildGroupedOptions($proxy);
    }
    elseif (!$this->cache->getInto($cacheId, $groupedOptions)) {
      $groupedOptions = $this->buildGroupedOptions($proxy);

      $this->cache->set($cacheId, $groupedOptions);
    }

    $replacement = new Formula_Select_Fixed($groupedOptions);

    if (!$replacement instanceof FormulaInterface) {
      return NULL;
    }

    return $this->buffer[$cacheId] = $replacement;
  }

  /**
   * @param \Donquixote\OCUI\Formula\Cache\Formula_Cache_SelectInterface $proxy
   *
   * @return array
   */
  private function buildGroupedOptions(Formula_Cache_SelectInterface $proxy): array {

    $optgroupLabels = $proxy->getOptgroupLabels();

    $groupedOptions = [];
    foreach ($proxy->getGroupedOptions() as $optgroupId => $optionsInGroup) {

      $optgroupLabel = $optgroupLabels[$optgroupId] ?? $optgroupId;

      if (!isset($groupedOptions[$optgroupLabel])) {
        $groupedOptions[$optgroupLabel] = $optionsInGroup;
      }
      else {
        // Support the edge case where two optgroup ids have the same label.
        $groupedOptions[$optgroupLabel] += $optionsInGroup;
      }
    }

    return $groupedOptions;
  }
}
