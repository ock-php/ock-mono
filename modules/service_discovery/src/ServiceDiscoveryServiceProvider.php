<?php

declare(strict_types = 1);

namespace Drupal\service_discovery;

use Drupal\service_discovery\Compiler\NeutrallyDecoratingPass;
use Ock\DependencyInjection\Provider\CommonServiceProvider;
use Symfony\Component\DependencyInjection\Compiler\RemoveUnusedDefinitionsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use function Ock\Helpers\array_filter_instanceof;

/**
 * Service provider for this module.
 */
class ServiceDiscoveryServiceProvider extends ModuleServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function doRegister(ContainerBuilder $container): void {
    // Register services and/or compiler passes from the package.
    (new CommonServiceProvider())->register($container);

    // Re-add compiler pass that is removed in installer.
    /* @see \Drupal\Core\Installer\NormalInstallerServiceProvider::register() */
    $pass_config = $container->getCompilerPassConfig();
    $passes = $pass_config->getRemovingPasses();
    $filtered = array_filter_instanceof($passes, RemoveUnusedDefinitionsPass::class);
    foreach ($filtered as $delta => $pass) {
      $passes[$delta] = new NeutrallyDecoratingPass($pass);
    }
    $pass_config->setRemovingPasses($passes);
  }

}
