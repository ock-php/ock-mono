<?php

use Ock\ClassDiscovery\Inspector\PackageInspector;
use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\ClassInspector_ServiceAttribute;

/**
 * Verify what happens if inspectors are added in the wrong order.
 */
return PackageInspector::fromCandidateObjects([
  ClassInspector_ServiceAttribute::create(),
  // The private services will replace the public services.
  ClassInspector_ClassAsPrivateService::create(),
]);
