<?php

use Ock\ClassDiscovery\Inspector\PackageInspector;
use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\ClassInspector_SymfonyAsAliasAttributeDecorator;

return PackageInspector::fromCandidateObjects([
  ClassInspector_ClassAsPrivateService::create(),
  ClassInspector_SymfonyAsAliasAttributeDecorator::create(...),
]);
