<?php

declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures;

use Ock\Adaptism\AdaptismPackage;
use Ock\ClassDiscovery\ReflectionClassesIA\CurrentNamespaceBase;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(AdaptismPackage::DISCOVERY_TAG_NAME)]
class AdaptismTestNamespace extends CurrentNamespaceBase {

}
