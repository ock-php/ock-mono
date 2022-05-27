<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter;

use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionList_Discovery;
use Donquixote\Adaptism\AdapterMap\AdapterMap_DefinitionList;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_DispatchByType;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Psr\Container\ContainerInterface;

class UniversalAdapter implements UniversalAdapterInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface $specificAdapter
   */
  public function __construct(
    private readonly SpecificAdapterInterface $specificAdapter,
  ) {}

  public static function fromClassFilesIA(
    ClassFilesIAInterface $classFilesIA,
    ContainerInterface $container,
  ): self {
    $dl = AdapterDefinitionList_Discovery::fromClassFilesIA($classFilesIA);
    $am = new AdapterMap_DefinitionList($dl, $container);
    $sa = new SpecificAdapter_DispatchByType($am);
    return new self($sa);
  }

  /**
   * {@inheritdoc}
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter = null,
  ): ?object {

    if ($adaptee instanceof $resultType) {
      return $adaptee;
    }

    return $this->specificAdapter->adapt(
      $adaptee,
      $resultType,
      $universalAdapter ?? $this,
    );
  }
}
