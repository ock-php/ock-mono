<?php
declare(strict_types=1);

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Drupal\renderkit\Schema\Misc\SelectByEt\SelectByEt_FieldName;

/**
 * Schema where the value is like 'body' for field 'node.body'.
 */
class CfSchema_FieldName extends CfSchema_FieldName_Base {

  /**
   * @param string $entityTypeId
   * @param string|null $bundleName
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function create($entityTypeId, $bundleName = NULL): CfSchemaInterface {

    return new CfSchema_SelectByEt(
      $entityTypeId,
      [$bundleName],
      new SelectByEt_FieldName());
  }

  /**
   * @param string $entityType
   * @param string|null $bundleName
   */
  public function __construct(
    $entityType,
    $bundleName = NULL
  ) {

    parent::__construct(
      $entityType,
      $bundleName,
      NULL);
  }
}
