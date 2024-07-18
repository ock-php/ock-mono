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
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  #[PrivateService]
  #[PrivateService(ConfigEntityStorageInterface::class)]
  #[PrivateService(ContentEntityStorageInterface::class)]
  public static function entityStorage(
    EntityTypeManagerInterface $entityTypeManager,
    #[GetParametricArgument(0)]
    string $entityTypeId,
  ): EntityStorageInterface {
    return $entityTypeManager->getStorage($entityTypeId);
  }

}
