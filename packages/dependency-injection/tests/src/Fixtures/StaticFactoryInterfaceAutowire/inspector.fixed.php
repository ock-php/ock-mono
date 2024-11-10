<?php

use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute;
use Ock\DependencyInjection\Inspector\PackageInspector_RegisterInterfacesReflection;

return [
  ClassInspector_ClassAsPrivateService::create(),
  FactoryInspector_ServiceAttribute::create(),
  PackageInspector_RegisterInterfacesReflection::decorateIfNeeded(...),
];
