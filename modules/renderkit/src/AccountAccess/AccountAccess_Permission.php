<?php
declare(strict_types=1);

namespace Drupal\renderkit\AccountAccess;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\ock\Formula\Formula_PermissionId;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Attribute\Plugin\OckPluginFormula;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Text\Text;

class AccountAccess_Permission implements AccountAccessInterface {

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   */
  #[OckPluginFormula(self::class, 'permission', 'Permission')]
  public static function formula(
    #[GetService]
    Formula_PermissionId $permissionFormula,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'permission',
        Text::t('Permission'),
        $permissionFormula,
      )
      ->construct(self::class);
  }

  /**
   * @param string $permission
   */
  public function __construct(
    private readonly string $permission,
  ) {}

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
