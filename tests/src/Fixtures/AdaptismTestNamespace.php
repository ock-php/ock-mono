<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures;

use Donquixote\Adaptism\AdaptismPackage;
use Donquixote\ClassDiscovery\ReflectionClassesIA\CurrentNamespaceBase;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(AdaptismPackage::DISCOVERY_TAG_NAME)]
class AdaptismTestNamespace extends CurrentNamespaceBase {

}
