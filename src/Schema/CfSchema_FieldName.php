<?php

namespace Drupal\renderkit8\Schema;

use Drupal\renderkit8\Schema\Misc\SelectByEt\SelectByEt_FieldName;

/**
 * Schema where the value is like 'body' for field 'node.body'.
 */
class CfSchema_FieldName extends CfSchema_FieldName_Base {

  /**
   * @param string $entityTypeId
   * @param string|null $bundleName
   *
   * @return self
   */
  public static function create($entityTypeId, $bundleName = NULL) {

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
