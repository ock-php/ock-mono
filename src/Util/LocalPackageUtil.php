<?php
declare(strict_types=1);

namespace Donquixote\Ock\Util;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_NamespaceDirectoryPsr4;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\NamespaceDirectory;
use Donquixote\Ock\Discovery\STADiscovery_X;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

final class LocalPackageUtil extends UtilBase {

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   */
  public static function collectIncarnators(ParamToValueInterface $paramToValue): array {

    return STADiscovery_X::create($paramToValue)
      ->classFilesIAGetPartials(self::getClassFilesIA());
  }

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
