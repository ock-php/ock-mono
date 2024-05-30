<?php

declare(strict_types=1);

namespace Drupal\renderkit_example;

use Ock\ClassDiscovery\ReflectionClassesIA\CurrentNamespaceBase;
use Ock\Ock\OckPackage;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Namespace to scan for ock plugins.
 */
#[AutoconfigureTag(OckPackage::DISCOVERY_TAG_NAME)]
class RenderkitExampleNamespace extends CurrentNamespaceBase {

}
