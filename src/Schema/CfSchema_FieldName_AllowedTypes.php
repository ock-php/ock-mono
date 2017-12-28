<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\IdToSchema\IdToSchema_Callback;
use Donquixote\Cf\IdToSchema\IdToSchema_Fixed;
use Donquixote\Cf\Schema\Select\CfSchema_Select_TwoStepFlatSelectComposite;

/**
 * Schema where the value is like 'body' for field 'node.body'.
 */
class CfSchema_FieldName_AllowedTypes extends CfSchema_FieldName_Base {

  /**
   * @var null|string[]
   */
  private $allowedFieldTypes;

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param string[]|null $allowedFieldTypes
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createEtDotFieldNameSchema(
    $entityTypeId = NULL,
    $bundle = NULL,
    array $allowedFieldTypes = NULL
  ) {

    $etToSchema = self::createEtToSchema(
      $entityTypeId,
      $bundle,
      $allowedFieldTypes);

    $signatureData = [
      $entityTypeId,
      $bundle,
      $allowedFieldTypes,
    ];

    $signature = sha1(serialize($signatureData));

    $cacheId = 'renderkit:schema:et_dot_field_name:entity_reference:' . $signature;

    // @todo What was this about?
    unset($cacheId);

    return new CfSchema_Select_TwoStepFlatSelectComposite(
      CfSchema_EntityType::create(),
      $etToSchema);
  }

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param string[]|null $allowedFieldTypes
   *
   * @return \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  public static function createEtToSchema(
    $entityTypeId = NULL,
    $bundle = NULL,
    array $allowedFieldTypes = NULL
  ) {

    if (NULL === $entityTypeId) {
      return new IdToSchema_Callback(
        function($selectedEntityTypeId) use ($allowedFieldTypes) {
          return new self(
            $selectedEntityTypeId,
            NULL,
            $allowedFieldTypes);
        });
    }

    // Only allow this one entity type.
    return new IdToSchema_Fixed(
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
