<?php

declare(strict_types=1);

namespace Ock\Testing\Exporter;

use Ock\ClassDiscovery\Reflection\ClassReflection;

/**
 * Exports as array suitable for a yaml file.
 */
class Exporter_ToYamlArray implements ExporterInterface {

  /**
   * @var array<class-string, \Closure(never&object, int, string|int|null, self): mixed>
   */
  private array $exportersByClass = [];

  /**
   * @template T of object
   *
   * @param class-string<T> $class
   * @param \Closure(T, int, string|int|null, self): mixed $exporter
   *
   * @return static
   */
  public function withDedicatedExporter(string $class, \Closure $exporter): static {
    $clone = clone $this;
    $clone->exportersByClass[$class] = $exporter;
    return $clone;
  }

  /**
   * @param class-string $class
   * @param array $keys_to_unset
   *
   * @return static
   */
  public function withObjectGetters(string $class, array $keys_to_unset = []): static {
    return $this->withDedicatedExporter($class, static function(
      object $object,
      int $depth,
      string|int|null $key,
      self $exporter,
    ) use ($keys_to_unset): array {
      $export = $exporter->doExportObject($object, $depth, true);
      foreach ($keys_to_unset as $key) {
        unset($export[$key]);
      }
      return $export;
    });
  }

  /**
   * @template T of object
   *
   * @param T&object $reference
   * @param class-string<T>|null $class
   *
   * @return static
   */
  public function withReferenceObject(object $reference, string $class = null): static {
    $class ??= \get_class($reference);
    $decorated = $this->exportersByClass[$class] ?? fn (
      object $object,
      int $depth,
      string|int|null $key,
      self $exporter,
    ): array => $exporter->doExportObject($object, $depth);
    return $this->withDedicatedExporter($class, static function (
      object $object,
      int $depth,
      string|int|null $key,
      self $exporter,
    ) use ($reference, $decorated): array {
      $compare_export = $decorated($reference, $depth, $key, $exporter);
      $export = $decorated($object, $depth, $key, $exporter);
      unset($compare_export['class']);
      return static::arrayDiffAssocStrict($export, $compare_export);
    });
  }

  /**
   * @template T of object
   *
   * @param class-string $class
   * @param \Closure(string|int|null): T $factory
   *
   * @return static
   */
  public function withReferenceObjectFactory(string $class, \Closure $factory): static {
    $decorated = $this->exportersByClass[$class] ?? fn (
      object $object,
      int $depth,
      string|int|null $key,
      self $exporter,
    ): array => $exporter->doExportObject($object, $depth);
    return $this->withDedicatedExporter($class, static function(
      object $object,
      int $depth,
      string|int|null $key,
      self $exporter,
    ) use ($factory, $decorated): array {
      $reference = $factory($key);
      $compare_export = $decorated($reference, $depth, $key, $exporter);
      $export = $decorated($object, $depth, $key, $exporter);
      unset($compare_export['class']);
      return static::arrayDiffAssocStrict($export, $compare_export);
    });
  }

  /**
   * {@inheritdoc}
   */
  public function export(mixed $value, string $label = null, int $depth = 2): mixed {
    $export = $this->exportRecursive($value, $depth);
    if ($label !== null) {
      $export = [$label => $export];
    }
    return $export;
  }

  /**
   * @param mixed $value
   * @param int $depth
   * @param string|int|null $key
   *
   * @return mixed
   */
  protected function exportRecursive(mixed $value, int $depth = 2, string|int|null $key = null): mixed {
    if (\is_array($value)) {
      if ($value === []) {
        return [];
      }
      if ($depth <= 0) {
        if (array_is_list($value)) {
          return '[...]';
        }
        else {
          return '{...}';
        }
      }
      return \array_map(
        fn ($k) => $this->exportRecursive($value[$k], $depth - 1, $k),
        \array_combine(array_keys($value), array_keys($value)),
      );
    }
    if (is_object($value)) {
      return $this->exportObject($value, $depth, $key);
    }
    if (\is_resource($value)) {
      return 'resource';
    }
    return $value;
  }

  /**
   * @param object $object
   * @param int $depth
   * @param int|string|null $key
   *
   * @return array
   */
  protected function exportObject(object $object, int $depth, int|string|null $key = null): array {
    foreach ($this->exportersByClass as $class => $callback) {
      if ($object instanceof $class) {
        return $callback($object, $depth, $key, $this);
      }
    }
    return $this->doExportObject($object, $depth);
  }

  /**
   * @param object $object
   * @param int $depth
   * @param bool $getters
   *
   * @return array
   */
  protected function doExportObject(object $object, int $depth, bool $getters = false): array {
    $reflectionClass = new \ReflectionClass($object);
    $classNameExport = $reflectionClass->getName();
    if ($reflectionClass->isAnonymous()) {
      if (\preg_match('#^(class@anonymous\\0/[^:]+:)\d+\$[0-9a-f]+$#', $classNameExport, $matches)) {
        // Replace the line number and the hash-like suffix.
        // This will make the asserted value more stable.
        $classNameExport = $matches[1] . '**';
      }
    }
    $export = ['class' => $classNameExport];
    if ($depth <= 0) {
      return $export;
    }
    if (!$getters) {
      $export += $this->exportObjectProperties($object, $depth - 1);
    }
    else {
      $export += $this->exportObjectProperties($object, $depth - 1, true);
      $export += $this->exportObjectGetterValues($object, $depth - 1);
    }
    return $export;
  }

  /**
   * @param object $object
   * @param int $depth
   * @param bool $public
   *   TRUE to only export public properties.
   *
   * @return array|string
   */
  protected function exportObjectProperties(object $object, int $depth, bool $public = false): array|string {
    $reflector = new ClassReflection($object);
    $properties = $reflector->getFilteredProperties(static: false, public: $public ?: null);
    $export = [];
    foreach ($properties as $property) {
      try {
        $propertyValue = $property->getValue($object);
      }
      catch (\Throwable) {
        $propertyValue = '(not initialized)';
      }
      $export['$' . $property->name] = $this->exportRecursive($propertyValue, $depth - 1);
    }
    return $export;
  }

  /**
   * @param object $object
   * @param int $depth
   *
   * @return array|string
   */
  protected function exportObjectGetterValues(object $object, int $depth): array|string {
    $reflector = new ClassReflection($object);
    $result = [];
    foreach ($reflector->getFilteredMethods(static: false, public: true, constructor: false) as $method) {
      if ($method->hasRequiredParameters()
        || (string) ($method->getReturnType() ?? '?') === 'void'
        || (string) ($method->getReturnType() ?? '?') === 'never'
        || !\preg_match('#^(get|is|has)[A-Z]#', $method->name)
      ) {
        // This is not a getter method.
        continue;
      }
      $value = $object->{$method->name}();
      $result[$method->name . '()'] = $this->exportRecursive($value, $depth - 1);
    }
    \ksort($result);
    return $result;
  }

  /**
   * @param array $a
   * @param array $b
   *
   * @return array
   */
  protected static function arrayDiffAssocStrict(array $a, array $b): array {
    $diff = \array_filter(
      $a,
      fn (mixed $v, string|int|null $k) => !\array_key_exists($k, $b)
        || $v !== $b[$k],
      \ARRAY_FILTER_USE_BOTH,
    );
    return $diff;
  }

}
