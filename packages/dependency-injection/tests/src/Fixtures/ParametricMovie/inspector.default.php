<?php

use Ock\DependencyInjection\Provider\ServiceProvider_ParametricServices;
use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute;

return [
  new ServiceProvider_ParametricServices(),
  ClassInspector_ClassAsPrivateService::create(),
  FactoryInspector_ServiceAttribute::create(),
];
