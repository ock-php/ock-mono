<?php

declare(strict_types=1);

namespace Ock\DependencyInjection;

use Ock\ClassDiscovery\FactsIA\FactsIAInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Registers services from a facts iterator into a container builder.
 */
class ServiceProvider {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\FactsIA\FactsIAInterface $factsIA
   */
  public function __construct(
    private readonly FactsIAInterface $factsIA,
  ) {}

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   */
  public function register(ContainerBuilder $container): void {
    foreach ($this->factsIA as $key => $fact) {
      if ($fact instanceof Definition) {
        $container->setDefinition($key, $fact);
      }
      elseif ($fact instanceof \Closure) {
        $fact($container, $key);
      }
    }
  }

}
