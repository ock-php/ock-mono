<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Drupal\renderkit\Formula\Misc\SelectByEt\SelectByEt_FieldName;

/**
 * Formula where the value is like 'body' for field 'node.body'.
 */
class Formula_FieldName extends Formula_FieldName_Base {

  /**
   * @param string $entityTypeId
   * @param string|null $bundleName
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function create($entityTypeId, $bundleName = NULL): FormulaInterface {

    return new Formula_SelectByEt(
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
