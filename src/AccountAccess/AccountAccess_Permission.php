<?php
declare(strict_types=1);

namespace Drupal\renderkit\AccountAccess;

use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_CallbackMono;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\faktoria\Schema\CfSchema_Select_Permission;

class AccountAccess_Permission implements AccountAccessInterface {

  /**
   * @var string
   */
  private $permission;

  /**
   * @CfrPlugin("permission", @t("Permission"))
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function schema() {
    return CfSchema_ValueToValue_CallbackMono::fromClass(
      self::class,
      CfSchema_Select_Permission::proxy());
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
  public function access(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission(
      $account,
      $this->permission);
  }
}
