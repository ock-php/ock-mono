<?php

declare(strict_types=1);

namespace Drupal\ock;

use Ock\Adaptism\AdaptismPackage;
use Ock\ClassDiscovery\ReflectionClassesIA\CurrentNamespaceBase;
use Ock\DependencyInjection\Attribute\ServiceTag;

/**
 * Classes to scan for adapter definitions.
 */
#[ServiceTag(AdaptismPackage::DISCOVERY_TAG_NAME)]
class OckModuleNamespace extends CurrentNamespaceBase {

}
