<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\IdToFormula\IdToFormula_Callback;
use Donquixote\ObCK\IdToFormula\IdToFormula_Fixed;
use Donquixote\ObCK\Formula\Select\Formula_Select_TwoStepFlatSelectComposite;

/**
 * Formula where the value is like 'body' for field 'node.body'.
 */
class Formula_FieldName_AllowedTypes extends Formula_FieldName_Base {

  /**
   * @var null|string[]
   */
  private $allowedFieldTypes;

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param string[]|null $allowedFieldTypes
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createEtDotFieldNameFormula(
    $entityTypeId = NULL,
    $bundle = NULL,
    array $allowedFieldTypes = NULL
  ) {

    $etToFormula = self::createEtToFormula(
      $entityTypeId,
      $bundle,
      $allowedFieldTypes);

    $signatureData = [
      $entityTypeId,
      $bundle,
      $allowedFieldTypes,
    ];

    $signature = sha1(serialize($signatureData));

    $cacheId = 'renderkit:formula:et_dot_field_name:entity_reference:' . $signature;

    // @todo What was this about?
    unset($cacheId);

    return new Formula_Select_TwoStepFlatSelectComposite(
      Formula_EntityType::create(),
      $etToFormula);
  }

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param string[]|null $allowedFieldTypes
   *
   * @return \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  public static function createEtToFormula(
    $entityTypeId = NULL,
    $bundle = NULL,
    array $allowedFieldTypes = NULL
  ) {

    if (NULL === $entityTypeId) {
      return new IdToFormula_Callback(
        function($selectedEntityTypeId) use ($allowedFieldTypes) {
          return new self(
            $selectedEntityTypeId,
            NULL,
            $allowedFieldTypes);
        });
    }

    // Only allow this one entity type.
    return new IdToFormula_Fixed(
      [
        $entityTypeId => new self(
          $entityTypeId,
          $bundle,
          $allowedFieldTypes)
      ]);
  }

  /**
   * @param string $entityTypeId
   * @param string|null $bundleName
   * @param string[]|null $allowedFieldTypes
   */
  public function __construct(
    $entityTypeId,
    $bundleName = NULL,
    array $allowedFieldTypes = NULL
  ) {

    $this->allowedFieldTypes = $allowedFieldTypes;

    parent::__construct(
      $entityTypeId,
      $bundleName,
      $allowedFieldTypes);
  }

  /**
   * @return \Drupal\Core\Field\FieldStorageDefinitionInterface[][]
   *   Format: $[$fieldTypeId][$fieldName] = $fieldStorageDefinition
   */
  protected function getStorageDefinitionsByType(): array {

    $storagesByType = parent::getStorageDefinitionsByType();

    if (NULL === $this->allowedFieldTypes) {
      return $storagesByType;
    }

    return array_intersect_key(
      $storagesByType,
      array_fill_keys($this->allowedFieldTypes, TRUE));
  }

}
