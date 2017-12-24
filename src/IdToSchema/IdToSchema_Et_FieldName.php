<?php
declare(strict_types=1);

namespace Drupal\renderkit8\IdToSchema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Drupal\renderkit8\Schema\CfSchema_FieldName_AllowedTypes;

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
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($entityTypeId): ?CfSchemaInterface {

    return new CfSchema_FieldName_AllowedTypes(
      $entityTypeId,
      $this->bundleName,
      $this->allowedFieldTypes);
  }
}
