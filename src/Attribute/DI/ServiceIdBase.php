<?php

declare(strict_types = 1);

namespace Drupal\ock\Attribute\DI;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
abstract class ServiceIdBase implements DependencyInjectionArgumentInterface {

  /**
   * {@inheritdoc}
   */
  public function getArgDefinition(\ReflectionParameter $parameter): Reference {
    return new Reference($this->paramGetServiceId($parameter));
  }

  /**
   * {@inheritdoc}
   */
  public function paramGetValue(\ReflectionParameter $parameter, ContainerInterface $container): mixed {
    return $container->get($this->paramGetServiceId($parameter));
  }

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return string
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  abstract protected function paramGetServiceId(\ReflectionParameter $parameter): string;

}
