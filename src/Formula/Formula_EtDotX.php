<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Proxy\Cache\Formula_Proxy_Cache_SelectBase;
use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Drupal\renderkit\Formula\Misc\SelectByEt\SelectByEtInterface;

class Formula_EtDotX extends Formula_Proxy_Cache_SelectBase {

  /**
   * @var \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface
   */
  private $etSelector;

  /**
   * @var \Drupal\renderkit\Formula\Misc\SelectByEt\SelectByEtInterface
   */
  private $selectByEt;

  /**
   * @var string
   */
  private $separator = '.';

  /**
   * @param \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface $etSelector
   * @param \Drupal\renderkit\Formula\Misc\SelectByEt\SelectByEtInterface $selectByEt
   * @param string $cacheId
   */
  public function __construct(Formula_FlatSelectInterface $etSelector, SelectByEtInterface $selectByEt, $cacheId) {
    $this->etSelector = $etSelector;
    $this->selectByEt = $selectByEt;
    parent::__construct($cacheId);
  }

  /**
   * @param string $separator
   *
   * @return static
   */
  public function withSeparator($separator): self {
    $clone = clone $this;
    $clone->separator = $separator;
    return $clone;
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function getGroupedOptions(): array {

    $groupedOptions = [];
    foreach ($this->etSelector->getOptions() as $entityTypeId => $entityTypeLabel) {
      $entityTypeLabel = (string)$entityTypeLabel;
      // The IDE has difficulties to recognize the type of $optionsInGroup.
      /** @var string[] $optionsInGroup */
      foreach ($this->selectByEt->etGetGroupedOptions($entityTypeId) as $groupLabel => $optionsInGroup) {
        foreach ($optionsInGroup as $k => $label) {
          $groupedOptions[$entityTypeLabel][$entityTypeId . $this->separator . $k] = $label . ' (' . $groupLabel . ')';
        }
      }
    }

    return $groupedOptions;
  }
}
