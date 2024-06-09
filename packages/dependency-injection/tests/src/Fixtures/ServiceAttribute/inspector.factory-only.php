<?php

use Ock\ClassDiscovery\Inspector\PackageInspector;
use Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute;

return PackageInspector::fromCandidateObjects([
  FactoryInspector_ServiceAttribute::create(),
]);
