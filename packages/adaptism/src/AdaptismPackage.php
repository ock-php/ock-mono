<?php

declare(strict_types = 1);

namespace Ock\Adaptism;

use Ock\DependencyInjection\Provider\PackageServiceProviderBase;

class AdaptismPackage extends PackageServiceProviderBase {

  const DIR = __DIR__;

  const DISCOVERY_TAG_NAME = 'adaptism.discovery';

}
