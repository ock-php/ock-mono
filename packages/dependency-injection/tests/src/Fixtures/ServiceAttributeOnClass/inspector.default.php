<?php

use Ock\ClassDiscovery\Inspector\PackageInspector;
use Ock\DependencyInjection\Inspector\ClassInspector_ServiceAttribute;

return PackageInspector::fromCandidateObjects([
  ClassInspector_ServiceAttribute::create(),
]);
