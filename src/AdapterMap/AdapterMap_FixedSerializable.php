<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterMap;

use Donquixote\Adaptism\Exception\AdapterException;
use Donquixote\Adaptism\Exception\AdapterNotAvailableException;
use Donquixote\Adaptism\Exception\MissingAdapterException;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Bridge;
use Donquixote\DID\Exception\ContainerToValueException;
use Psr\Container\ContainerInterface;

class AdapterMap_FixedSerializable implements AdapterMapInterface {

  /**
   * @var array<class-string, array<string, true>>
   */
  private array $idsBySourceType = [];

  /**
   * @var array<class-string, array<string, true>>
   */
  private array $idsBySourceTypeExpanded = [];

  /**
   * @var array<class-string|'object', array<string, true>>
   */
  private array $idsByResultType = ['object' => []];

  /**
   * @var array<string, array<(class-string|"object"), true>>
   */
  private array $resultTypesById;

  /**
   * @var \Donquixote\DID\ContainerToValue\ContainerToValueInterface<\Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface>[]
   */
  private array $adapterCTVs = [];

  /**
   * @var \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  private array $adapters = [];

  /**
   * Constructor.
   *
   * @param \Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface[] $definitions
   * @param \Psr\Container\ContainerInterface $container
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public function __construct(
    array $definitions,
    private readonly ContainerInterface $container,
  ) {
    $ids = \array_keys($definitions);
    $this->resultTypesById = \array_fill_keys($ids, []);
    $specifities = [];
    foreach ($definitions as $id => $definition) {
      $specifities[$id] = $definition->getSpecifity();
      $resultType = $definition->getResultType();
      $this->adapterCTVs[$id] = $definition->getAdapterCTV();
      if ($resultType !== null) {
        try {
          foreach ($this->typeExpandParents($resultType, false) as $resultParentType) {
            $this->resultTypesById[$id][$resultParentType] = true;
            $this->idsByResultType[$resultParentType][$id] = $id;
          }
        }
        catch (\ReflectionException $e) {
          throw new AdapterNotAvailableException(\sprintf(
            'Unknown result type on %s: %s',
            $id,
            $e->getMessage(),
          ));
        }
      }
      else {
        $this->resultTypesById[$id]['object'] = true;
        $this->idsByResultType['object'][$id] = $id;
      }
      $this->idsBySourceType[$definition->getSourceType() ?? 'object'][$id] = $id;
    }
    \array_multisort($specifities, $this->resultTypesById);
  }

  /**
   * {@inheritdoc}
   */
  public function getSuitableAdapters(?string $adapteeType, ?string $resultType): \Iterator {
    $resultTypesById = $this->resultTypesById;
    $resultTypesById1 = ($adapteeType !== null)
      ? \array_intersect_key(
        $resultTypesById,
        $this->idsBySourceTypeExpanded[$adapteeType]
          ??= $this->sourceTypeCollectIds($adapteeType),
      )
      : $resultTypesById;
    $resultTypesById2 = ($resultType !== null)
      ? \array_intersect_key(
        $resultTypesById1,
        ($this->idsByResultType[$resultType] ?? []) + $this->idsByResultType['object'],
      )
      : $resultTypesById1;
    try {
      foreach ($resultTypesById2 as $id => $_) {
        yield $id => $this->adapters[$id]
          ??= $this->adapterCTVs[$id]->containerGetValue($this->container);
      }
      foreach ($resultTypesById1 as $id => $bridgeTypes) {
        $bridgeTypes1 = \array_intersect_key($bridgeTypes, $this->idsBySourceType);
        if (!$bridgeTypes1) {
          continue;
        }
        $decorated = $this->adapters[$id]
          ??= $this->adapterCTVs[$id]->containerGetValue($this->container);
        foreach ($bridgeTypes1 as $bridgeType => $_) {
          yield $id . ':' . $bridgeType => new SpecificAdapter_Bridge($decorated, $bridgeType);
        }
      }
    }
    catch (ContainerToValueException $e) {
      throw new AdapterException($e->getMessage(), 0, $e);
    }
  }

  /**
   * @param class-string $sourceType
   *
   * @return array<string, string>
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  private function sourceTypeCollectIds(string $sourceType): array {
    try {
      $sourceTypeParents = $this->typeExpandParents($sourceType, true);
    }
    catch (\ReflectionException $e) {
      throw new MissingAdapterException(\sprintf(
        'Unknown source type: %s',
        $e->getMessage(),
      ));
    }
    return \array_replace([], ...\array_values(\array_intersect_key(
      $this->idsBySourceType,
      $sourceTypeParents,
    )));
  }

  /**
   * @param class-string $type
   *
   * @return array<class-string, class-string>
   *
   * @throws \ReflectionException
   */
  private function typeExpandParents(string $type, bool $includeObject): array {
    $resultReflectionClass = new \ReflectionClass($type);
    $parents = $resultReflectionClass->getInterfaceNames();
    $parents[] = $type;
    while ($resultReflectionClass = $resultReflectionClass->getParentClass()) {
      $parents[] = $resultReflectionClass->getName();
    }
    if ($includeObject) {
      $parents[] = 'object';
    }
    return array_combine($parents, $parents);
  }

  public function __serialize(): array {
    return [
      'resultTypesById' => $this->resultTypesById,
      'idsBySourceType' => $this->idsBySourceType,
      'idsByResultType' => $this->idsByResultType,
      'factories' => $this->adapterCTVs,
    ];
  }

  public function __unserialize(array $data): void {
    [
      'resultTypesById' => $this->resultTypesById,
      'idsBySourceType' => $this->idsBySourceType,
      'idsByResultType' => $this->idsByResultType,
      'factories' => $this->adapterCTVs,
    ] = $data;
  }

}
