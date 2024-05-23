<?php

declare(strict_types=1);

namespace Drupal\renderkit;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\ParametricService;
use Donquixote\DID\Attribute\Parameter\GetArgument;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

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
  #[ParametricService]
  #[ParametricService(ConfigEntityStorageInterface::class)]
  #[ParametricService(ContentEntityStorageInterface::class)]
  public static function entityStorage(
    #[GetService('entity_type.manager')]
    EntityTypeManagerInterface $entityTypeManager,
    #[GetArgument]
    string $entityTypeId,
  ): EntityStorageInterface {
    return $entityTypeManager->getStorage($entityTypeId);
  }

}
