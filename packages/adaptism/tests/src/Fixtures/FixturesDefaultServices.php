<?php

declare(strict_types = 1);

namespace Donquixote\Adaptism\Tests\Fixtures;

use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionList_Discovery;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\DID\Attribute\Service;

class FixturesDefaultServices {

  #[Service]
  public static function getDateTimeZone(): \DateTimeZone {
    return new \DateTimeZone('America/New_York');
  }

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   *
   * @throws \ReflectionException
   */
  #[Service(serviceIdSuffix: AdapterDefinitionList_Discovery::class)]
  public static function getAdapterClassFilesIA(): ClassFilesIAInterface {
    return ClassFilesIA::psr4FromClass(FixturesUtil::class);
  }

}
