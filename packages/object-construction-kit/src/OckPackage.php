<?php

declare(strict_types=1);

namespace Ock\Ock;

use Ock\ClassDiscovery\Discovery\DiscoveryInterface;
use Ock\ClassDiscovery\Discovery\FactoryDiscovery;
use Ock\ClassDiscovery\Inspector\FactoryInspector_Concat;
use Ock\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_Concat;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

class OckPackage {

  const NAMESPACE = __NAMESPACE__;

  const DIR = __DIR__;

  const DISCOVERY_TARGET = 'ockDiscovery';

  const DISCOVERY_TAG_NAME = 'ock.discovery';

}
