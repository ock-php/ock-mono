<?php

declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures;

use Ock\Adaptism\AdaptismPackage;
use Ock\ClassDiscovery\ReflectionClassesIA\CurrentNamespaceBase;
use Ock\DependencyInjection\Attribute\ServiceTag;

#[ServiceTag(AdaptismPackage::DISCOVERY_TAG_NAME)]
class AdaptismTestNamespace extends CurrentNamespaceBase {

}
