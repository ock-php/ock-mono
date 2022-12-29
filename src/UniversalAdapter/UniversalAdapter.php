<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter;

use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface;
use Donquixote\Adaptism\AdapterMap\AdapterMap_DefinitionList;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_DispatchByType;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
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

  /**
   * @param \Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface $adapterDefinitionList
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return self
   */
  #[Service]
  public static function create(
    #[GetService]
    AdapterDefinitionListInterface $adapterDefinitionList,
    #[GetService]
    ContainerInterface $container,
  ): self {
    $adapterMap = new AdapterMap_DefinitionList($adapterDefinitionList, $container);
    $specificAdapter = new SpecificAdapter_DispatchByType($adapterMap);
    return new self($specificAdapter);
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
