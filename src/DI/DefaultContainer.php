<?php

declare(strict_types = 1);

namespace Donquixote\Adaptism\DI;

use Donquixote\Adaptism\DI\AdaptismDefaultServices;
use Donquixote\DID\Container\Container_CTVs;
use Donquixote\DID\ContainerToValue\ContainerToValue_Container;
use Donquixote\DID\CTVList\CTVList_Discovery_ServiceAttribute;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Psr\Container\ContainerInterface;

class DefaultContainer {

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface[] $classFilesIAs
   *
   * @return \Psr\Container\ContainerInterface
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  public static function fromClassFilesIAs(array $classFilesIAs): ContainerInterface {
    $containerDiscoveryClassFilesIA = ClassFilesIA::multiple($classFilesIAs);
    $emptyCtvList = new CTVList_Discovery_ServiceAttribute(
      AdaptismDefaultServices::getParamToCTV(),
    );
    $ctvs = $emptyCtvList
      ->withClassFilesIA($containerDiscoveryClassFilesIA)
      ->getCTVs();
    $ctvs[ContainerInterface::class] = new ContainerToValue_Container();
    return new Container_CTVs($ctvs);
  }

}
