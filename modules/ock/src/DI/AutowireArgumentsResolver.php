<?php

declare(strict_types = 1);

namespace Drupal\ock\DI;

use Drupal\Component\Utility\ArgumentsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AutowireArgumentsResolver implements ArgumentsResolverInterface {

  public function __construct(
    protected readonly ContainerInterface $container,
  ) {}

  public function getArguments(callable $callable): array {
    // TODO: Implement getArguments() method.
  }

}
