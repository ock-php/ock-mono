<?php

declare(strict_types = 1);

namespace Donquixote\Ock;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

class OckPackage {

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   * @throws \ReflectionException
   */
  public function getServiceDiscoveryNamespaces(): ClassFilesIAInterface {
    return ClassFilesIA::psr4FromClass(self::class);
  }

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   * @throws \ReflectionException
   */
  public function getAdapterDiscoveryNamespaces(): ClassFilesIAInterface {
    return ClassFilesIA::psr4FromClass(self::class);
  }

}
