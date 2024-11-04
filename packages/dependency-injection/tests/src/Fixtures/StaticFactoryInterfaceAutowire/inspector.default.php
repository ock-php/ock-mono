<?php

/**
 * @file
 * In this scenario, autowire does not work correctly, due to a bug in symfony.
 *
 * See the 'fixed' scenario where one more inspector is added to fix autowire.
 */

use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute;

return [
  ClassInspector_ClassAsPrivateService::create(),
  FactoryInspector_ServiceAttribute::create(),
];
