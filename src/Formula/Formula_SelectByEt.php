<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Proxy\Cache\Formula_Proxy_Cache_SelectBase;
use Drupal\renderkit\Formula\Misc\SelectByEt\SelectByEtInterface;

class Formula_SelectByEt extends Formula_Proxy_Cache_SelectBase {

  /**
   * @var null|string[]
   */
  private $bundleNames;

  /**
   * @var \Drupal\renderkit\Formula\Misc\SelectByEt\SelectByEtInterface
   */
  private $selectByEt;

  /**
   * @param string $entityTypeId
   * @param string[]|null $bundleNames
   * @param \Drupal\renderkit\Formula\Misc\SelectByEt\SelectByEtInterface $selectByEt
   */
  public function __construct(private $entityTypeId, array $bundleNames = NULL, SelectByEtInterface $selectByEt) {
    $this->bundleNames = $bundleNames;
    $this->selectByEt = $selectByEt;

    $signatureData = [
      static::class,
      $entityTypeId,
      $bundleNames,
      $selectByEt->getCacheId(),
    ];

    $cacheId = sha1(serialize($signatureData));

    parent::__construct($cacheId);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function getGroupedOptions(): array {

    return $this->selectByEt->etGetGroupedOptions(
      $this->entityTypeId,
      $this->bundleNames);
  }
}
