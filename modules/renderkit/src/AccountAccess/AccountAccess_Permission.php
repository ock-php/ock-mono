<?php
declare(strict_types=1);

namespace Drupal\renderkit\AccountAccess;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\Ock\Attribute\Plugin\OckPluginFormula;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\ock\Formula\Formula_PermissionId;

class AccountAccess_Permission implements AccountAccessInterface {

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
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
