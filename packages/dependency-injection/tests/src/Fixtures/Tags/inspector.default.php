<?php

use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\ClassInspector_SymfonyAutoconfigureAttribute;
use Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute;
use Ock\DependencyInjection\Provider\ServiceProvider_ServiceModifierAttribute;

return [
  ClassInspector_ClassAsPrivateService::create(),
  FactoryInspector_ServiceAttribute::create(),
  new ServiceProvider_ServiceModifierAttribute(),
  new ClassInspector_SymfonyAutoconfigureAttribute(),
];
