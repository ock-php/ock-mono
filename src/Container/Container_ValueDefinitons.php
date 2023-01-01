<?php

declare(strict_types = 1);

namespace Donquixote\DID\Container;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\DID\ContainerToValue\ContainerToValue_Container;
use Donquixote\DID\CTVList\CTVList_Discovery_ServiceAttribute;
use Donquixote\DID\Exception\ContainerToValueException;
use Donquixote\DID\ParamToCTV\ParamToCTV;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetContainer;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;
use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;
use Psr\Container\ContainerInterface;

class Container_ValueDefinitons implements ContainerInterface {

  /**
   * @var array
   */
  private array $cache = [];

  /**
   * Constructor.
   *
   * @param array<string, \Donquixote\DID\ValueDefinition\ValueDefinitionInterface> $definitions
   */
  public function __construct(
    private readonly array $definitions,
  ) {}

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface[] $classFilesIAs
   *
   * @return \Psr\Container\ContainerInterface
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  public static function fromClassFilesIAs(array $classFilesIAs): ContainerInterface {
    $containerDiscoveryClassFilesIA = ClassFilesIA::multiple($classFilesIAs);
    $emptyCtvList = new CTVList_Discovery_ServiceAttribute(
      ParamToCTV::create(),
    );
    $ctvs = $emptyCtvList
      ->withClassFilesIA($containerDiscoveryClassFilesIA)
      ->getCTVs();
    $ctvs[ContainerInterface::class] = new ContainerToValue_Container();
    return new Container_CTVs($ctvs);
  }

  /**
   * @template T
   *
   * @param class-string<T>|string $id
   *
   * @return T|mixed
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  public function get(string $id): mixed {
    return $this->cache[$id]
      ??= $this->build($this->definitions[$id] ?? $this->fail($id));
  }

  /**
   * @param string $id
   *
   * @return never
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  private function fail(string $id): never {
    throw new ContainerToValueException(sprintf(
      'Cannot retrieve service %s.',
      $id,
    ));
  }

  /**
   * @param mixed $definition
   *
   * @return mixed
   *
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   *
   * @todo Make this pluggable.
   */
  private function build(mixed $definition): mixed {
    if (is_array($definition)) {
      $result = [];
      foreach ($definition as $k => $v) {
        $result[$k] = $this->build($v);
      }
      return $result;
    }

    if (!$definition instanceof ValueDefinitionInterface) {
      return $definition;
    }

    if ($definition instanceof ValueDefinition_GetService) {
      return $this->get($definition->id);
    }

    if ($definition instanceof ValueDefinition_GetContainer) {
      return $this;
    }

    if ($definition instanceof ValueDefinition_Construct) {
      $class = $this->build($definition->class);
      $args = $this->build($definition->args);
      return new $class(...$args);
    }

    if ($definition instanceof ValueDefinition_Call) {
      $callable = $this->build($definition->callback);
      $args = $this->build($definition->args);
      return $callable(...$args);
    }

    if ($definition instanceof ValueDefinition_CallObjectMethod) {
      $object = $this->build($definition->object);
      $method = $this->build($definition->method);
      $args = $this->build($definition->args);
      return $object->$method(...$args);
    }

    throw new \RuntimeException(
      sprintf(
        'Unknown value definition type %s.',
        get_class($definition),
      )
    );
  }

  /**
   * {@inheritdoc}
   */
  public function has(string $id): bool {
    return isset($this->ctvs[$id]);
  }

}
