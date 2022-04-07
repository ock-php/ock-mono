<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterMap;

use Donquixote\Adaptism\Exception\AdapterNotAvailableException;
use Donquixote\Adaptism\Exception\MissingAdapterException;
use Psr\Container\ContainerInterface;

class AdapterMap_FixedSerializable implements AdapterMapInterface {

  /**
   * @var array<string, true>
   */
  private array $truthsById;

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
   * @var \Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainerInterface[]
   */
  private array $factories = [];

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
    private ContainerInterface $container,
  ) {
    $specifities = [];
    foreach ($definitions as $id => $definition) {
      $specifities[$id] = $definition->getSpecifity();
      $resultType = $definition->getResultType();
      $this->factories[$id] = $definition->getFactory();
      if ($resultType !== null) {
        try {
          foreach ($this->typeExpandParents($resultType, false) as $resultParentType) {
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
        $this->idsByResultType['object'][$id] = $id;
      }
      $this->idsBySourceType[$definition->getSourceType() ?? 'object'][$id] = $id;
    }
    $ids = \array_keys($definitions);
    $this->truthsById = \array_combine($ids, $ids);
    \array_multisort($specifities, $this->truthsById);
  }

  /**
   * {@inheritdoc}
   */
  public function getSuitableAdapters(?string $source_type, ?string $result_type): array {
    $ids = $this->truthsById;
    if ($source_type !== null) {
      $ids = \array_intersect_key(
        $ids,
        $this->idsBySourceTypeExpanded[$source_type]
          ??= $this->sourceTypeCollectIds($source_type)
      );
    }
    if ($result_type !== null) {
      $ids = \array_intersect_key(
        $ids,
        $this->idsByResultType[$result_type] + $this->idsByResultType['object']);
    }
    $adapters = [];
    foreach ($ids as $id => $_) {
      $adapters[$id] = $this->adapters[$id]
        ??= $this->factories[$id]->createAdapter($this->container);
    }
    return $adapters;
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
      'ids' => $this->truthsById,
      'idsBySourceType' => $this->idsBySourceType,
      'idsByResultType' => $this->idsByResultType,
      'factories' => $this->factories,
    ];
  }

  public function __unserialize(array $data): void {
    [
      'ids' => $this->truthsById,
      'idsBySourceType' => $this->idsBySourceType,
      'idsByResultType' => $this->idsByResultType,
      'factories' => $this->factories,
    ] = $data;
  }

}
