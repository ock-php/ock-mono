<?php

use Ock\ClassDiscovery\Inspector\PackageInspector;
use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\ClassInspector_ServiceAttribute;
use Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute;

/**
 * Verify what happens if inspectors are added in the wrong order.
 */
return PackageInspector::fromCandidateObjects([
  ClassInspector_ServiceAttribute::create(),
  FactoryInspector_ServiceAttribute::create(),
  // The implicit private services will replace the public services.
  // This is generally not the indended outcome.
  ClassInspector_ClassAsPrivateService::create(),
]);
