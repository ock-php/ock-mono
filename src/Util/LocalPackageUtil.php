<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Util;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_NamespaceDirectoryPsr4;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\NamespaceDirectory;
use Donquixote\OCUI\Discovery\STADiscovery_X;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

final class LocalPackageUtil extends UtilBase {

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  public static function collectSTAPartials(ParamToValueInterface $paramToValue): array {

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
