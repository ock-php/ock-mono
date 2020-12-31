<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaToAnything;

use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Exception\SchemaToAnythingException;
use Donquixote\Cf\Util\LocalPackageUtil;

class SchemaToAnything_SmartChain implements SchemaToAnythingInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[][][]
   *   Format: $[$schemaType][$targetType] = $partials
   */
  private $partialsGrouped = [];

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[][][]
   *   Format: $[$targetType][$schemaType] = $partials
   */
  private $partialsGroupedReverse = [];

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[][]
   *   Format: $[$targetType] = $partials
   */
  private $partialsByTargetType = [];

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[][]
   *   Format: $[$schemaType] = $partials
   */
  private $partialsBySchemaType = [];

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   */
  public static function create(ParamToValueInterface $paramToValue): self {
    $partials = LocalPackageUtil::collectSTAPartials($paramToValue);
    return new self($partials);
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $partials
   */
  public function __construct(array $partials) {

    $indices = [];
    $specifities = [];
    $i = 0;
    foreach ($partials as $partial) {
      ++$i;
      $indices[] = $i;
      $specifities[] = $partial->getSpecifity();
    }

    array_multisort($specifities, SORT_DESC, $indices, $partials);

    $this->partials = $partials;
  }

  /**
   * @return int
   */
  public function getSpecifity(): int {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function schema(CfSchemaInterface $schema, string $interface) {

    if ($schema instanceof $interface) {
      return $schema;
    }

    $partials = $this->schemaTypeAndTargetTypeGetPartials(
      \get_class($schema),
      $interface);

    if ([] === $partials) {
      // No partials available for given types.
      return NULL;
    }

    foreach ($partials as $partial) {

      try {
        $candidate = $partial->schema($schema, $interface, $this);
      }
      catch (SchemaToAnythingException $e) {
        // @todo Log this!
        unset($e);
        continue;
      }

      if (null !== $candidate) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
      }
    }

    // Partials returned nothing.
    return NULL;
  }

  /**
   * @param string $schemaType
   * @param string $targetType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function schemaTypeAndTargetTypeGetPartials(string $schemaType, string $targetType): array {

    return $this->partialsGrouped[$schemaType][$targetType]
      ?? ($this->partialsGrouped[$schemaType][$targetType] = $this->schemaTypeAndTargetTypeCollectPartials(
        $schemaType,
        $targetType));
  }

  /**
   * @param string $schemaType
   * @param string $targetType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function schemaTypeAndTargetTypeCollectPartials(string $schemaType, string $targetType): array {

    return $this->partialsGroupedReverse[$targetType][$schemaType] = array_intersect_key(
      $this->schemaTypeGetPartials($schemaType),
      $this->targetTypeGetPartials($targetType));
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function targetTypeGetPartials(string $interface): array {

    return $this->partialsByTargetType[$interface]
      ?? $this->partialsByTargetType[$interface] = $this->targetTypeCollectPartials($interface);
  }

  /**
   * @param string $targetType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function targetTypeCollectPartials(string $targetType): array {

    $partials = [];
    /** @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->providesResultType($targetType)) {
        // Preserve keys for array_intersect().
        $partials[$k] = $partial;
      }
    }

    return $partials;
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function schemaTypeGetPartials(string $interface): array {

    return $this->partialsBySchemaType[$interface]
      ?? $this->partialsBySchemaType[$interface] = $this->schemaTypeCollectPartials($interface);
  }

  /**
   * @param string $schemaType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function schemaTypeCollectPartials(string $schemaType): array {

    $partials = [];
    /** @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->acceptsSchemaClass($schemaType)) {
        // Preserve keys for array_intersect().
        $partials[$k] = $partial;
      }
    }

    return $partials;
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function providesResultType(string $interface): bool {
    return [] !== $this->targetTypeGetPartials($interface);
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function acceptsSchemaClass(string $interface): bool {
    return [] !== $this->schemaTypeGetPartials($interface);
  }
}
