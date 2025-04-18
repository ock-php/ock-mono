<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Provider;

use Ock\ClassDiscovery\FactsIA\FactsIAInterface;
use Ock\DependencyInjection\ServiceDefinitionUtil;
use Ock\Helpers\Util\MessageUtil;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ServiceProvider_FactsIA implements ServiceProviderInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\FactsIA\FactsIAInterface $factsIA
   */
  public function __construct(
    private readonly FactsIAInterface $factsIA,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container): void {
    foreach ($this->factsIA as $key => $fact) {
      if ($fact instanceof Definition) {
        if (!is_string($key)) {
          throw new \RuntimeException(
            sprintf(
              "Expected a string key with a service definition, found %s.",
              // In an iterator, the key could be anything.
              MessageUtil::formatValue($key),
            )
          );
        }
        if ($fact->getTag(ServiceDefinitionUtil::TENTATIVE_TAG)) {
          if ($container->has($key)) {
            // Don't overwrite existing definition.
            continue;
          }
          $fact->clearTag(ServiceDefinitionUtil::TENTATIVE_TAG);
        }
        $container->setDefinition($key, $fact);
      }
      elseif ($fact instanceof Alias) {
        if (!is_string($key)) {
          throw new \RuntimeException(sprintf(
            "Expected a string key with alias '%s', found %s.",
            $fact->__toString(),
            // In an iterator, the key could be anything.
            MessageUtil::formatValue($key),
          ));
        }
        $container->setAlias($key, $fact);
      }
      elseif ($fact instanceof \Closure) {
        $fact($container, $key);
      }
    }
  }

}
