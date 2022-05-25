<?php

declare(strict_types=1);

namespace Donquixote\Ock\Util;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_NamespaceDirectoryPsr4;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\NamespaceDirectory;

final class LocalPackageUtil extends UtilBase {

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  public static function getClassFilesIA(): ClassFilesIAInterface {
    return ClassFilesIA_NamespaceDirectoryPsr4::createFromNsdirObject(
      self::getNamespaceDir());
  }

  /**
   * @return \Donquixote\ClassDiscovery\NamespaceDirectory
   */
  public static function getNamespaceDir(): NamespaceDirectory {
    return NamespaceDirectory::create(__DIR__, __NAMESPACE__)
      ->basedir();
  }

}
