<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\LocalPackageUtil;

class SchemaToAnythingPartial_SmartChain implements SchemaToAnythingPartialInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[][][]
   *   Format: $[$schemaType][$targetType] = $partials
   */
  private $partialsGrouped = [];

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
   *
   * @throws \Donquixote\Cf\Exception\STABuilderException
   */
  public static function create(ParamToValueInterface $paramToValue): SchemaToAnythingPartial_SmartChain {
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
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public function schema(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
  ): ?object {

    $partials = $this->schemaTypeAndTargetTypeGetPartials(
      \get_class($schema),
      $interface);

    foreach ($partials as $partial) {
      if (NULL !== $candidate = $partial->schema($schema, $interface, $helper)) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
      }
    }

    # if ($interface !== EmptinessInterface::class) {
      # $schemaClass = get_class($schema);
      # kdpm($partials, "$schemaClass -> $interface");
      # kdpm($this->partialsByTargetType, '$this->partialsByTargetType');
      # kdpm($this->partialsBySchemaType, '$this->partialsBySchemaType');
      # kdpm($this->partials, '$this->partials');
    # }

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

    return array_intersect_key(
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
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return [] !== $this->targetTypeGetPartials($resultInterface);
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsSchemaClass(string $schemaClass): bool {
    return [] !== $this->schemaTypeGetPartials($schemaClass);
  }
}
