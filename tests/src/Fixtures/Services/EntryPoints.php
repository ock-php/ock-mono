<?php

declare(strict_types = 1);

namespace Donquixote\DID\Tests\Fixtures\Services;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\DID\ServiceDefinition\ServiceDefinitionList_Discovery;
use Donquixote\DID\ServiceDefinition\ServiceDefinitionListInterface;

class EntryPoints {

  public static function getServiceDefinitionList(): ServiceDefinitionListInterface {
    return (new ServiceDefinitionList_Discovery())
      ->withClassFilesIA(self::getServicesClassFilesIA());
  }

  public static function getServicesClassFilesIA(): ClassFilesIAInterface {
    return ClassFilesIA::psr4FromClass(self::class);
  }

}
