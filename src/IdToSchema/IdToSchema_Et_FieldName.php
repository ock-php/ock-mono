<?php

namespace Drupal\renderkit8\IdToSchema;

use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Drupal\renderkit8\Schema\CfSchema_FieldName_AllowedTypesX;

class IdToSchema_Et_FieldName implements IdToSchemaInterface {

  /**
   * @var null|string[]
   */
  private $allowedFieldTypes;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param null|string[] $allowedFieldTypes
   * @param string|null $bundleName
   */
  public function __construct(
    array $allowedFieldTypes = NULL,
    $bundleName = NULL
  ) {
    $this->allowedFieldTypes = $allowedFieldTypes;
    $this->bundleName = $bundleName;
  }

  /**
   * @param string|int $entityTypeId
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($entityTypeId) {

    return CfSchema_FieldName_AllowedTypesX::create(
      $this->allowedFieldTypes,
      $entityTypeId,
      $this->bundleName);
  }
}
