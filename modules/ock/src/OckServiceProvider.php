<?php

declare(strict_types = 1);

namespace Drupal\ock;

use Drupal\ock\Attribute\DI\PublicService;
use Drupal\service_discovery\ModuleServiceProviderBase;
use Ock\Adaptism\AdaptismPackage;
use Ock\Egg\EggPackage;
use Ock\Ock\OckPackage;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Service provider for the Ock module.
 */
class OckServiceProvider extends ModuleServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function doRegister(ContainerBuilder $container): void {
    // Register services in the composer packages.
    (new EggPackage())->register($container);
    (new AdaptismPackage())->register($container);
    (new OckPackage())->register($container);

    // Make services public that have the
    $container->registerAttributeForAutoconfiguration(
      PublicService::class,
      static function (ChildDefinition $definition, PublicService $attribute, \ReflectionClass $class_or_method): void {
        $definition->setPublic(true);
      },
    );

    // Register packages in the module itself.
    parent::doRegister($container);
  }

}
