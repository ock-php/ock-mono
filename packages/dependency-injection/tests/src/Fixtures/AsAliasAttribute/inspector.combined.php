<?php

use Ock\ClassDiscovery\Inspector\PackageInspector;
use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\ClassInspector_SymfonyAsAliasAttributeDecorator;
use Ock\DependencyInjection\Inspector\PackageInspector_SinglyImplementedInterfaceAliasDecorator;

return PackageInspector::fromCandidateObjects([
  ClassInspector_ClassAsPrivateService::create(),
  PackageInspector_SinglyImplementedInterfaceAliasDecorator::create(...),
  ClassInspector_SymfonyAsAliasAttributeDecorator::create(...),
]);
