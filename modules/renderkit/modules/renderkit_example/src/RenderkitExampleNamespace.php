<?php

declare(strict_types=1);

namespace Drupal\renderkit_example;

use Ock\ClassDiscovery\ReflectionClassesIA\CurrentNamespaceBase;
use Ock\DependencyInjection\Attribute\ServiceTag;
use Ock\Ock\OckPackage;

/**
 * Namespace to scan for ock plugins.
 */
#[ServiceTag(OckPackage::DISCOVERY_TAG_NAME)]
class RenderkitExampleNamespace extends CurrentNamespaceBase {

}
