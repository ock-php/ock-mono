<?php
declare(strict_types=1);

namespace Drupal\ock\Formula;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Ock\DependencyInjection\Attribute\Service;
use Drupal\user\PermissionHandlerInterface;
use Ock\Ock\Formula\Select\Formula_SelectInterface;
use Ock\Ock\Text\Text;
use Ock\Ock\Text\TextInterface;
use Psr\Container\ContainerInterface;

/**
 * A legend to choose a permission from the Drupal permission system.
 *
 * @see \views_plugin_access_perm
 */
#[Service]
class Formula_PermissionId implements Formula_SelectInterface {

  /**
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return self
   */
  public static function fromContainer(ContainerInterface $container): self {
    return new self(
      $container->get('user.permissions'),
      $container->get('module_handler'));
  }

  /**
   * Constructor.
   *
   * @param \Drupal\user\PermissionHandlerInterface $permissionHandler
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   */
  public function __construct(
    private readonly PermissionHandlerInterface $permissionHandler,
    private readonly ModuleHandlerInterface $moduleHandler
  ) {}

  public function getOptionsMap(): array {
    $map = [];
    foreach ($this->permissionHandler->getPermissions() as $id => $permission) {
      $map[$id] = $permission['provider'];
    }
    return $map;
  }

  public function groupIdGetLabel(int|string $groupId): ?TextInterface {
    return Text::s($this->moduleHandler->getName($groupId));
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    $permission = $this->permissionHandler->getPermissions()[$id] ?? NULL;
    if ($permission === NULL) {
      return NULL;
    }
    // @todo What is the actual value of 'title'?
    return Text::t(strip_tags($permission['title']));
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {

    $permissions = $this->permissionHandler->getPermissions();

    return isset($permissions[$id]);
  }

}
