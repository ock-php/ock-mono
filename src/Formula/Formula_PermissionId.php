<?php
declare(strict_types=1);

namespace Drupal\ock\Formula;

use Donquixote\Ock\Formula\Select\Formula_Select_BufferedBase;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\user\PermissionHandlerInterface;
use Psr\Container\ContainerInterface;

/**
 * A legend to choose a permission from the Drupal permission system.
 *
 * @see \views_plugin_access_perm
 */
class Formula_PermissionId extends Formula_Select_BufferedBase {

  /**
   * @var \Drupal\user\PermissionHandlerInterface
   */
  private $permissionHandler;

  /**
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  private $moduleHandler;

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
    PermissionHandlerInterface $permissionHandler,
    ModuleHandlerInterface $moduleHandler
  ) {
    $this->permissionHandler = $permissionHandler;
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {

    foreach ($this->permissionHandler->getPermissions() as $id => $permission) {
      $grouped_options[$permission['provider']][$id] = Text::t(
        strip_tags($permission['title']));
    }

    foreach ($grouped_options as $provider => $provider_options) {
      $group_labels[$provider] = Text::s(
        $this->moduleHandler->getName($provider));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {

    $permissions = $this->permissionHandler->getPermissions();

    if (isset($permissions[$id])) {
      return Text::t(
        strip_tags($permissions[$id]['title']));
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {

    $permissions = $this->permissionHandler->getPermissions();

    return isset($permissions[$id]);
  }

}
