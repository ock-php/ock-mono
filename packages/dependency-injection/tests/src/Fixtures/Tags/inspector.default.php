<?php

use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute;
use Ock\DependencyInjection\Provider\ServiceProvider_ServiceModifierAttribute;
use Ock\DependencyInjection\Provider\ServiceProviderInterface;
use Ock\DependencyInjection\Tests\Fixtures\Tags\AutoTaggingInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return [
  ClassInspector_ClassAsPrivateService::create(),
  FactoryInspector_ServiceAttribute::create(),
  new ServiceProvider_ServiceModifierAttribute(),
  new class implements ServiceProviderInterface {
    public function register(ContainerBuilder $container): void {
      $container->registerForAutoconfiguration(AutoTaggingInterface::class)
        ->addTag('sunny');
    }
  },
];
