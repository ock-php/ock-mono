<?php

declare(strict_types=1);

namespace Drupal\ock_example;

use Ock\ClassDiscovery\ReflectionClassesIA\CurrentNamespaceBase;
use Ock\Ock\OckPackage;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Classes to scan for adapter definitions.
 */
#[AutoconfigureTag(OckPackage::DISCOVERY_TAG_NAME)]
class OckExampleNamespace extends CurrentNamespaceBase {

}
