<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Proxy\Cache\CfSchema_Proxy_Cache_SelectBase;
use Drupal\renderkit8\Schema\Misc\SelectByEt\SelectByEtInterface;

class CfSchema_EtDotX_FixedEt extends CfSchema_Proxy_Cache_SelectBase {

  /**
   * @var string
   */
  private $entityTypeId;

  /**
   * @var null|string[]
   */
  private $bundleNames;

  /**
   * @var \Drupal\renderkit8\Schema\Misc\SelectByEt\SelectByEtInterface
   */
  private $selectByEt;

  /**
   * @var string
   */
  private $separator = '.';

  /**
   * @param string $entityTypeId
   * @param string[]|null $bundleNames
   * @param \Drupal\renderkit8\Schema\Misc\SelectByEt\SelectByEtInterface $selectByEt
   * @param string $cacheId
   */
  public function __construct($entityTypeId, array $bundleNames = NULL, SelectByEtInterface $selectByEt, $cacheId) {
    $this->entityTypeId = $entityTypeId;
    $this->bundleNames = $bundleNames;
    $this->selectByEt = $selectByEt;
    parent::__construct($cacheId);
  }

  /**
   * @param string $separator
   *
   * @return \Drupal\renderkit8\Schema\CfSchema_EtDotX_FixedEt
   */
  public function withSeparator(string $separator): self {
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
    // IDE fails to recognize this type.
    /** @var string[] $optionsInGroup */
    foreach ($this->selectByEt->etGetGroupedOptions(
      $this->entityTypeId,
      $this->bundleNames
    ) as $groupLabel => $optionsInGroup) {
      foreach ($optionsInGroup as $k => $label) {
        $groupedOptions[$groupLabel][$this->entityTypeId . $this->separator . $k] = $label;
      }
    }

    return $groupedOptions;
  }
}
