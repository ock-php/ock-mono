<?php

declare(strict_types=1);

namespace Ock\Adaptism\AdapterMap;

use Ock\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
class AdapterMap_DefinitionList implements AdapterMapInterface {

  /**
   * @var \Ock\Adaptism\AdapterMap\AdapterMapInterface|null
   */
  private ?AdapterMapInterface $realAdapterMap = null;

  /**
   * Constructor.
   *
   * @param \Ock\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface $definitionList
   * @param \Psr\Container\ContainerInterface $container
   */
  public function __construct(
    private readonly AdapterDefinitionListInterface $definitionList,
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
