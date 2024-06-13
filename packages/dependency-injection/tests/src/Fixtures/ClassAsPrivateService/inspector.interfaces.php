<?php

use Ock\ClassDiscovery\Inspector\PackageInspector;
use Ock\DependencyInjection\Inspector\ClassInspector_InterfaceAsFact;

return PackageInspector::fromCandidateObjects([
  new ClassInspector_InterfaceAsFact(),
]);
