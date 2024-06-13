<?php

use Ock\ClassDiscovery\Inspector\PackageInspector;
use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\ClassInspector_ServiceAttribute;

return PackageInspector::fromCandidateObjects([
  ClassInspector_ClassAsPrivateService::create(),
  // This inspector has to come later, because it will replace some private
  // services added earlier with public ones.
  ClassInspector_ServiceAttribute::create(),
]);
