<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything;

use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Exception\FormulaToAnythingException;
use Donquixote\OCUI\Util\LocalPackageUtil;

class FormulaToAnything_SmartChain implements FormulaToAnythingInterface {

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[][][]
   *   Format: $[$schemaType][$targetType] = $partials
   */
  private $partialsGrouped = [];

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[][][]
   *   Format: $[$targetType][$schemaType] = $partials
   */
  private $partialsGroupedReverse = [];

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[][]
   *   Format: $[$targetType] = $partials
   */
  private $partialsByTargetType = [];

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[][]
   *   Format: $[$schemaType] = $partials
   */
  private $partialsByFormulaType = [];

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  private $partials;

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface
   */
  private $sta;

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
   * @param \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[] $partials
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

    $this->sta = $this;
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
  public function schema(FormulaInterface $schema, string $interface) {

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
      catch (FormulaToAnythingException $e) {
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
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
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
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  private function schemaTypeAndTargetTypeCollectPartials(string $schemaType, string $targetType): array {

    return $this->partialsGroupedReverse[$targetType][$schemaType] = array_intersect_key(
      $this->schemaTypeGetPartials($schemaType),
      $this->targetTypeGetPartials($targetType));
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  private function targetTypeGetPartials(string $interface): array {

    return $this->partialsByTargetType[$interface]
      ?? $this->partialsByTargetType[$interface] = $this->targetTypeCollectPartials($interface);
  }

  /**
   * @param string $targetType
   *
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  private function targetTypeCollectPartials(string $targetType): array {

    $partials = [];
    /** @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface $partial */
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
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  private function schemaTypeGetPartials(string $interface): array {

    return $this->partialsByFormulaType[$interface]
      ?? $this->partialsByFormulaType[$interface] = $this->schemaTypeCollectPartials($interface);
  }

  /**
   * @param string $schemaType
   *
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  private function schemaTypeCollectPartials(string $schemaType): array {

    $partials = [];
    /** @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->acceptsFormulaClass($schemaType)) {
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
  public function acceptsFormulaClass(string $interface): bool {
    return [] !== $this->schemaTypeGetPartials($interface);
  }
}
