<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Proxy\Cache\CfSchema_Proxy_Cache_SelectBase;
use Drupal\Core\Entity\FieldableEntityInterface;

abstract class CfSchema_EtDotFieldName_ProxyCacheBase extends CfSchema_Proxy_Cache_SelectBase {

  /**
   * @var null|string
   */
  private $entityTypeId;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundleName
   * @param string $extraCacheId
   */
  public function __construct(
    $entityTypeId = NULL,
    $bundleName = NULL,
    $extraCacheId
  ) {
    $this->entityTypeId = $entityTypeId;
    $this->bundleName = $bundleName;

    $signatureData = [
      $entityTypeId,
      $bundleName,
      $extraCacheId,
    ];

    $signature = sha1(serialize($signatureData)) . ':' . microtime(TRUE);

    $cacheId = 'renderkit:schema:et_dot_field_name:' . $signature;

    parent::__construct($cacheId);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function getGroupedOptions() {

    if (NULL !== $this->entityTypeId) {

      $groupedOptions = [];
      foreach ($this->etGetGroupedOptions(
        $this->entityTypeId,
        $this->bundleName
      ) as $groupLabel => $optionsInGroup) {

        foreach ($optionsInGroup as $fieldName => $fieldLabel) {
          $groupedOptions[$groupLabel][$this->entityTypeId . '.' . $fieldName] = $fieldLabel;
        }
      }

      return $groupedOptions;
    }

    $groupedOptions = [];
    foreach ($this->getFieldableEntityTypeLabels() as $entityTypeId => $entityTypeLabel) {

      foreach ($this->etGetGroupedOptions($entityTypeId) as $groupLabel => $optionsInGroup) {
        foreach ($optionsInGroup as $fieldName => $fieldLabel) {
          $groupedOptions[$groupLabel][$entityTypeId . '.' . $fieldName] = $entityTypeLabel . ': ' . $fieldLabel;
        }
      }
    }

    return $groupedOptions;
  }

  /**
   * @return string[]
   */
  private function getFieldableEntityTypeLabels() {

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    $etm = \Drupal::service('entity_type.manager');

    $entityTypeLabels = [];
    foreach ($etm->getDefinitions() as $entityTypeId => $entityTypeDefinition) {

      if (!is_a(
        $entityTypeDefinition->getClass(),
        FieldableEntityInterface::class,
        TRUE)
      ) {
        continue;
      }

      $entityTypeLabels[$entityTypeId] = $entityTypeDefinition->getLabel();
    }

    return $entityTypeLabels;
  }

  /**
   * @param string $entityTypeId
   * @param string|null $bundleName
   *
   * @return string[][]
   *   Format: $[$groupLabel][$fieldName] = $fieldLabel
   */
  abstract protected function etGetGroupedOptions($entityTypeId, $bundleName = NULL);
}

