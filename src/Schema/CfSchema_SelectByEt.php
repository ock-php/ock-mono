<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Proxy\Cache\CfSchema_Proxy_Cache_SelectBase;
use Drupal\renderkit8\Schema\Misc\SelectByEt\SelectByEtInterface;

class CfSchema_SelectByEt extends CfSchema_Proxy_Cache_SelectBase {

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
   * @param string $entityTypeId
   * @param string[]|null $bundleNames
   * @param \Drupal\renderkit8\Schema\Misc\SelectByEt\SelectByEtInterface $selectByEt
   */
  public function __construct($entityTypeId, array $bundleNames = NULL, SelectByEtInterface $selectByEt) {
    $this->entityTypeId = $entityTypeId;
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
