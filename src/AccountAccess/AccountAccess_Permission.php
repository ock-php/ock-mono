<?php
declare(strict_types=1);

namespace Drupal\renderkit\AccountAccess;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue_CallbackMono;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\faktoria\Formula\Formula_Select_Permission;

class AccountAccess_Permission implements AccountAccessInterface {

  /**
   * @var string
   */
  private $permission;

  /**
   * @CfrPlugin("permission", @t("Permission"))
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function formula(): FormulaInterface {
    return Formula_ValueToValue_CallbackMono::fromClass(
      self::class,
      Formula_Select_Permission::proxy());
  }

  /**
   * @param string $permission
   */
  public function __construct($permission) {
    $this->permission = $permission;
  }

  /**
   * @param \Drupal\Core\Session\AccountInterface $account
   *
   * @return \Drupal\Core\Access\AccessResult
   */
  public function access(AccountInterface $account): AccessResult {
    return AccessResult::allowedIfHasPermission(
      $account,
      $this->permission);
  }
}
