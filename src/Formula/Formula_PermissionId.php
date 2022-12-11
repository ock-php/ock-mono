<?php
declare(strict_types=1);

namespace Drupal\ock\Formula;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Ock\Formula\Select\Formula_Select_BufferedBase;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\ock\Attribute\DI\RegisterService;
use Drupal\user\PermissionHandlerInterface;
use Psr\Container\ContainerInterface;

/**
 * A legend to choose a permission from the Drupal permission system.
 *
 * @see \views_plugin_access_perm
 */
#[RegisterService]
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
    #[GetService('user.permissions')]
    private readonly PermissionHandlerInterface $permissionHandler,
    #[GetService('module_handler')]
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
