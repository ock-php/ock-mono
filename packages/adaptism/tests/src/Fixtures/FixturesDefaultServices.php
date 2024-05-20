<?php

declare(strict_types = 1);

namespace Ock\Adaptism\Tests\Fixtures;

use Ock\Adaptism\AdapterDefinitionList\AdapterDefinitionList_Discovery;
use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Ock\DID\Attribute\Service;

class FixturesDefaultServices {

  #[Service]
  public static function getDateTimeZone(): \DateTimeZone {
    return new \DateTimeZone('America/New_York');
  }

  /**
   * @return \Ock\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   *
   * @throws \ReflectionException
   */
  #[Service(serviceIdSuffix: AdapterDefinitionList_Discovery::class)]
  public static function getAdapterClassFilesIA(): ClassFilesIAInterface {
    return ClassFilesIA::psr4FromClass(FixturesUtil::class);
  }

}
