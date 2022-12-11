<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Proxy\Cache\Formula_Proxy_Cache_SelectBase;
use Drupal\Core\Entity\FieldableEntityInterface;

abstract class Formula_EtDotFieldName_ProxyCacheBase extends Formula_Proxy_Cache_SelectBase {

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundleName
   * @param string $extraCacheId
   */
  public function __construct(
    private readonly ?string $entityTypeId = NULL,
    private readonly ?string $bundleName = NULL,
    string $extraCacheId
  ) {
    $signatureData = [
      $entityTypeId,
      $bundleName,
      $extraCacheId,
    ];
    $signature = sha1(serialize($signatureData)) . ':' . microtime(TRUE);
    $cacheId = 'renderkit:formula:et_dot_field_name:' . $signature;
    parent::__construct($cacheId);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function getGroupedOptions(): array {
    $groupedOptions = [];

    if (NULL !== $this->entityTypeId) {
      foreach ($this->etGetGroupedOptions(
        $this->entityTypeId,
        $this->bundleName,
      ) as $groupLabel => $optionsInGroup) {
        foreach ($optionsInGroup as $fieldName => $fieldLabel) {
          $groupedOptions[$groupLabel][$this->entityTypeId . '.' . $fieldName] = $fieldLabel;
        }
      }
      return $groupedOptions;
    }

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
  private function getFieldableEntityTypeLabels(): array {

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
  abstract protected function etGetGroupedOptions(string $entityTypeId, string $bundleName = NULL): array;
}

