<?php

declare(strict_types = 1);

namespace Drupal\ock;

use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use Drupal\ock\DI\ResilientServiceAlias;
use Ock\Adaptism\AdaptismPackage;
use Ock\DID\DidNamespace;
use Ock\Egg\EggNamespace;
use Ock\Ock\OckPackage;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CheckReferenceValidityPass;
use Symfony\Component\DependencyInjection\Compiler\DefinitionErrorExceptionPass;
use Symfony\Component\DependencyInjection\Compiler\ReplaceAliasByActualDefinitionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class OckServiceProvider implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   *   Malformed service declaration.
   */
  public function register(ContainerBuilder $container): void {
    // The Drupal container builder makes all aliases public, thus prevengin
    // the removal of unused services.
    // Use a symfony ContainerBuilder instead.
    $new_builder = new ContainerBuilder();
    $definitions_drupal = $container->getDefinitions();
    $aliases_drupal = $container->getAliases();
    #$new_builder->merge($container);

    // Import some definitions from the existing container.
    $drupal_service_ids = [
      'container.trait',
      'logger.factory',
      'logger.channel_base',
      ContainerInterface::class,
      \Drupal\Component\DependencyInjection\ContainerInterface::class,
      \Symfony\Component\DependencyInjection\ContainerInterface::class,
    ];
    foreach ($drupal_service_ids as $id) {
      if ($container->hasDefinition($id)) {
        $new_builder->setDefinition($id, clone $container->getDefinition($id));
      }
      if ($container->hasAlias($id)) {
        $new_builder->setAlias($id, clone $container->getAlias($id));
      }
    }

    $definitions_before = $new_builder->getDefinitions();
    $aliases_before = $new_builder->getAliases();
    $map_before = $definitions_before + $aliases_before;

    static::loadPackageServicesPhp($new_builder, dirname(DidNamespace::DIR));
    static::loadPackageServicesPhp($new_builder, dirname(EggNamespace::DIR));
    static::loadPackageServicesPhp($new_builder, dirname(AdaptismPackage::DIR));
    static::loadPackageServicesPhp($new_builder, dirname(OckPackage::DIR));
    static::loadPackageServicesPhp($new_builder, dirname(__DIR__));

    $aliases = $new_builder->getAliases();
    foreach ($aliases as $id => $alias) {
      if ($alias->isPrivate()) {
        $aliases[$id] = ResilientServiceAlias::fromAlias($alias);
      }
    }
    $new_builder->setAliases($aliases);

    $container->merge($new_builder);

    return;

    $definitions_pre_compile = array_map(
      static fn ($definition) => unserialize(serialize($definition)),
      $new_builder->getDefinitions(),
    );
    $aliases_pre_compile = array_map(
      static fn ($alias) => unserialize(serialize($alias)),
      $new_builder->getAliases(),
    );

    $compiler_pass_config = $new_builder->getCompilerPassConfig();
    $removing_passes = $compiler_pass_config->getRemovingPasses();
    $removing_passes_filtered = \array_filter(
      $removing_passes,
      static fn ($pass) => !$pass instanceof ReplaceAliasByActualDefinitionPass,
    );
    $compiler_pass_config->setRemovingPasses($removing_passes_filtered);

    #$new_builder->setParameter('cache_contexts', []);
    try {
      $new_builder->compile();
    }
    catch (\Throwable $e) {
      if (in_array($e->getTrace()[0]['class'] ?? NULL,  [
        DefinitionErrorExceptionPass::class,
        CheckReferenceValidityPass::class,
      ])) {
        // The compilation failed, but that's ok.
        $x = 5;
      }
      else {
        $trace = $e->getTrace();
        throw $e;
      }
    }
    #(new AutowirePass(false))->process($new_builder);
    #(new RemovePrivateAliasesPass())->process($new_builder);
    # (new RemoveUnusedDefinitionsPass())->process($new_builder);
    #(new RemoveAbstractDefinitionsPass())->process($new_builder);

    $definitions_after = $new_builder->getDefinitions();
    $aliases_after = $new_builder->getAliases();
    $map_after = $definitions_after + $aliases_after;
    $map_after_new = \array_diff_key($map_after, $map_before);

    $definitions_new_remaining = \array_intersect_key($definitions_pre_compile, $map_after_new);
    $aliases_new_remaining = \array_intersect_key($aliases_pre_compile, $map_after_new);

    $container->addDefinitions($definitions_new_remaining);
    $container->addAliases($aliases_new_remaining);

    #$container->merge($new_builder);
  }

  /**
   * Loads services from a services.php in a package directory.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   *   Container builder.
   * @param string $dir
   *   Directory where the services.php is found.
   * @param string $file
   *   File name.
   *
   * @throws \Exception
   *   Bad arguments, or a file is not found.
   */
  protected static function loadPackageServicesPhp(ContainerBuilder $container, string $dir, string $file = 'services.php'): void {
    // The Drupal container builder makes all aliases public, thus prevengin
    // the removal of unused services.
    // Use a symfony ContainerBuilder instead.
    $new_builder = $container;
    $locator = new FileLocator($dir);
    $loader = new PhpFileLoader($new_builder, $locator);
    $loader->load($file);
    return;

    $aliases = $new_builder->getAliases();
    $private_aliases = array_filter(
      $aliases,
      static fn ($alias) => !$alias->isPublic(),
    );
    $container->merge($new_builder);
    print __METHOD__ . "\n";
    \var_export(\array_keys($private_aliases));
    print "\n";
    foreach ($private_aliases as $id => $alias) {
      $container->getAlias($id)->setPublic(false);
      $alias->setPublic(false);
    }
  }

}
