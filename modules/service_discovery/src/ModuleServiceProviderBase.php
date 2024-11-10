<?php

declare(strict_types = 1);

namespace Drupal\service_discovery;

use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use Drupal\ock\DI\ResilientServiceAlias;
use Ock\DependencyInjection\Inspector\PackageInspector_RegisterInterfacesReflection;
use Ock\DependencyInjection\Provider\PackageServiceProviderBase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Base class for service providers that scan entire directories.
 *
 * This is a feature that works in symfony effortlessly, but that is not that
 * trivial in Drupal.
 * The mechanism from symfony will at first register all classes found in a
 * directory as services, which may include a lot of services that would not
 * actually work. Later, unused services are removed by a compiler pass, which
 * generally includes these "broken" services.
 *
 * In Drupal, all services are marked as public by default, which prevents the
 * removal of unused services, and thus leaves broken service definitions.
 *
 * This class provides a mechanism to make these discovered services remain
 * private, so that they can be removed by the compiler pass later.
 *
 * In symfony, the directory loading would be activated through a directive in
 * the services.yml. Here it just happens directly in the service provider
 * class.
 *
 * By default, a module service provider that extends this class will scan the
 * entire module's src directory. To scan only specific directories, you can
 * override the ::doRegister() method.
 */
abstract class ModuleServiceProviderBase extends PackageServiceProviderBase implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   *   Malformed service declaration.
   */
  public function register(ContainerBuilder $container): void {
    // The Drupal container builder makes all aliases public, thus preventing
    // the removal of unused services.
    // Use a symfony ContainerBuilder instead.
    $new_builder = new ContainerBuilder();
    // Set the private property 'compiler'.
    // This is needed so that all compiler passes added to the symfony container
    // are also added to the Drupal container.
    (new \ReflectionClass($new_builder))
      ->getProperty('compiler')
      ->setValue($new_builder, $container->getCompiler());
    // Give access to parameters like 'container.modules'.
    (new \ReflectionClass($new_builder))
      ->getProperty('parameterBag')
      ->setValue($new_builder, $container->getParameterBag());

    $this->doRegister($new_builder);

    $aliases = $new_builder->getAliases();
    foreach ($aliases as $id => $alias) {
      if ($alias->isPrivate()) {
        $aliases[$id] = ResilientServiceAlias::fromAlias($alias);
      }
    }
    $new_builder->setAliases($aliases);

    $container->merge($new_builder);

    PackageInspector_RegisterInterfacesReflection::mergeFromOtherContainer($container, $new_builder);
  }

  /**
   * Registers services discovered in the module's own namespace directory.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   *   Replacement container builder that allows private services.
   *
   * @throws \Exception
   *   Malformed service declaration.
   *   (not here, but in sub-classes that override this method)
   */
  protected function doRegister(ContainerBuilder $container): void {
    // Discover services in the module's 'src/' directory.
    parent::register($container);
  }

}
