<?php

use Ock\ClassDiscovery\Inspector\PackageInspector;
use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\ClassInspector_ServiceAttribute;
use Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute;

return PackageInspector::fromCandidateObjects([
  ClassInspector_ClassAsPrivateService::create(),
  ClassInspector_ServiceAttribute::create(),
  FactoryInspector_ServiceAttribute::create(),
]);
