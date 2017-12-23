<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Proxy\Cache\CfSchema_Proxy_Cache_SelectBase;
use Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface;
use Drupal\renderkit8\Schema\Misc\SelectByEt\SelectByEtInterface;

class CfSchema_EtDotX extends CfSchema_Proxy_Cache_SelectBase {

  /**
   * @var \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface
   */
  private $etSelector;

  /**
   * @var \Drupal\renderkit8\Schema\Misc\SelectByEt\SelectByEtInterface
   */
  private $selectByEt;

  /**
   * @var string
   */
  private $separator = '.';

  /**
   * @param \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface $etSelector
   * @param \Drupal\renderkit8\Schema\Misc\SelectByEt\SelectByEtInterface $selectByEt
   * @param string $cacheId
   */
  public function __construct(CfSchema_FlatSelectInterface $etSelector, SelectByEtInterface $selectByEt, $cacheId) {
    $this->etSelector = $etSelector;
    $this->selectByEt = $selectByEt;
    parent::__construct($cacheId);
  }

  /**
   * @param string $separator
   *
   * @return \Drupal\renderkit8\Schema\CfSchema_EtDotX_FixedEt
   */
  public function withSeparator($separator) {
    $clone = clone $this;
    $clone->separator = $separator;
    return $clone;
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function getGroupedOptions() {

    $groupedOptions = [];
    foreach ($this->etSelector->getOptions() as $entityTypeId => $entityTypeLabel) {
      $entityTypeLabel = (string)$entityTypeLabel;
      foreach ($this->selectByEt->etGetGroupedOptions($entityTypeId) as $groupLabel => $optionsInGroup) {
        foreach ($optionsInGroup as $k => $label) {
          $groupedOptions[$entityTypeLabel][$entityTypeId . $this->separator . $k] = $label . ' (' . $groupLabel . ')';
        }
      }
    }

    return $groupedOptions;
  }
}
