<?php

declare(strict_types=1);

namespace Drupal\renderkit;

use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument;
use Ock\DependencyInjection\Attribute\PrivateService;

/**
 * Some service factories that don't have their own class.
 *
 * @see \Drupal\ock\OckServiceProvider
 */
class RenderkitServiceFactories {

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entityTypeId
   *
   * @return \Drupal\Core\Entity\EntityStorageInterface
   */
  #[PrivateService]
  public static function entityStorage(
    EntityTypeManagerInterface $entityTypeManager,
    #[GetParametricArgument(0)]
    string $entityTypeId,
  ): EntityStorageInterface {
    return $entityTypeManager->getStorage($entityTypeId);
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entityTypeId
   *
   * @return \Drupal\Core\Config\Entity\ConfigEntityStorageInterface
   */
  #[PrivateService]
  public static function configEntityStorage(
    EntityTypeManagerInterface $entityTypeManager,
    #[GetParametricArgument(0)]
    string $entityTypeId,
  ): ConfigEntityStorageInterface {
    $storage = $entityTypeManager->getStorage($entityTypeId);
    \assert($storage instanceof ConfigEntityStorageInterface);
    return $storage;
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entityTypeId
   *
   * @return \Drupal\Core\Entity\ContentEntityStorageInterface
   */
  #[PrivateService]
  public static function contentEntityStorage(
    EntityTypeManagerInterface $entityTypeManager,
    #[GetParametricArgument(0)]
    string $entityTypeId,
  ): ContentEntityStorageInterface {
    $storage = $entityTypeManager->getStorage($entityTypeId);
    \assert($storage instanceof ContentEntityStorageInterface);
    return $storage;
  }

}
