<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterMap;

use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Psr\Container\ContainerInterface;

#[Service]
class AdapterMap_DefinitionList implements AdapterMapInterface {

  /**
   * @var \Donquixote\Adaptism\AdapterMap\AdapterMapInterface|null
   */
  private ?AdapterMapInterface $realAdapterMap = null;

  /**
   * Constructor.
   *
   * @param \Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface $definitionList
   * @param \Psr\Container\ContainerInterface $container
   */
  public function __construct(
    #[GetService]
    private readonly AdapterDefinitionListInterface $definitionList,
    #[GetService]
    private readonly ContainerInterface $container,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getSuitableAdapters(?string $adapteeType, ?string $resultType): \Iterator {
    // @todo Cache the proxy.
    $proxy = $this->realAdapterMap ??= new AdapterMap_FixedSerializable(
      $this->definitionList->getDefinitions(),
      $this->container,
    );
    return $proxy->getSuitableAdapters($adapteeType, $resultType);
  }

}
