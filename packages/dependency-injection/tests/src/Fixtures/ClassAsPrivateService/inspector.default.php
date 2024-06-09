<?php

use Ock\ClassDiscovery\Inspector\PackageInspector;
use Ock\ClassDiscovery\Inspector\PackageInspectorInterface;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Tests\Fixtures\ClassAsPrivateService\ServiceWithDependency;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return PackageInspector::fromCandidateObjects([
  ClassInspector_ClassAsPrivateService::create(),
  new class implements PackageInspectorInterface {
    public function findInPackage(ReflectionClassesIAInterface $package): \Iterator {
      $label = '# Test callback to make one service public.';
      yield $label => static function (ContainerBuilder $container): void {
        // Make at least one service public.
        $container->getDefinition(ServiceWithDependency::class)
          ->setPublic(true);
      };
    }

  },
]);
