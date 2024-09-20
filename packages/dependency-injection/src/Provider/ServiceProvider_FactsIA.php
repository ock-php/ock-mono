<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Provider;

use Ock\ClassDiscovery\FactsIA\FactsIAInterface;
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
        $container->setDefinition($key, $fact);
        $x = 5;
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
    $x = 5;
  }

  public function foo(): array {
    $counter = 0;
    return [
      function () use (&$counter) {++$counter;},
      fn () => $counter,
    ];
  }

  public function f(): array {
    $parametric = [];
    $parameterized = [];
    return [
      function (mixed $key, mixed $fact) use (&$parametric, &$parameterized): bool {
        if (!is_string($key)) {
          return false;
        }
        if (\str_starts_with($key, 'parametric.')
          && $fact instanceof \Closure
        ) {
          $parametric[$key] = $fact;
          return true;
        }
        elseif (\str_starts_with($key, 'parameterized.')
          // @todo This one might not be a closure.
          && $fact instanceof \Closure
        ) {
          $parameterized[$key] = $fact;
          return true;
        }
        return false;
      },
      function (): \Closure {
        return function (ContainerBuilder $container): void {

        };
      }
    ];
  }

}
