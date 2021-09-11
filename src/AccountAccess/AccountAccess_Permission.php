<?php
declare(strict_types=1);

namespace Drupal\renderkit\AccountAccess;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue_CallbackMono;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\cu\Formula\Formula_PermissionId;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AccountAccess_Permission implements AccountAccessInterface {

  /**
   * @var string
   */
  private string $permission;

  /**
   * @CfrPlugin("permission", @t("Permission"))
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function formula(ContainerInterface $container): FormulaInterface {
    return Formula_ValueToValue_CallbackMono::fromClass(
      self::class,
      Formula_PermissionId::fromContainer($container));
  }

  /**
   * @param string $permission
   */
  public function __construct(string $permission) {
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
