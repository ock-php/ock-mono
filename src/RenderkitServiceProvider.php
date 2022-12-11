<?php
declare(strict_types=1);

namespace Drupal\renderkit;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use Drupal\ock\Util\ServiceDiscoveryUtil;
use Drupal\renderkit\Formula\Formula_EntityType;
use Drupal\renderkit\Helper\FieldDefinitionLookup;
use Drupal\renderkit\TextLookup\TextLookup_EntityId;

/**
 * Class with static methods for easier construction of display handlers.
 */
class RenderkitServiceProvider implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   *   Malformed service declaration.
   */
  public function register(ContainerBuilder $container): void {
    ServiceDiscoveryUtil::discoverInClassFiles(
      $container,
      ClassFilesIA::psr4FromClasses([
        FieldDefinitionLookup::class,
        Formula_EntityType::class,
        TextLookup_EntityId::class,
      ]),
    );
  }

}
