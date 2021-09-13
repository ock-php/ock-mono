<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Util\LocalPackageUtil;
use Donquixote\Ock\Util\MessageUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class Incarnator_SmartChain extends IncarnatorBase {

  /**
   * @var \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[][][]
   *   Format: $[$formulaType][$targetType] = $partials
   */
  private $partialsGrouped = [];

  /**
   * @var \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[][][]
   *   Format: $[$targetType][$formulaType] = $partials
   */
  private $partialsGroupedReverse = [];

  /**
   * @var \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[][]
   *   Format: $[$targetType] = $partials
   */
  private $partialsByTargetType = [];

  /**
   * @var \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[][]
   *   Format: $[$formulaType] = $partials
   */
  private $partialsByFormulaType = [];

  /**
   * @var \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   * @param string $cache_id
   *
   * @return self
   */
  public static function create(ParamToValueInterface $paramToValue, string $cache_id): self {
    $partials = LocalPackageUtil::collectIncarnators($paramToValue);
    return new self($partials, $cache_id);
  }

  /**
   * @param \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[] $partials
   * @param string $cache_id
   */
  public function __construct(array $partials, string $cache_id) {
    parent::__construct($cache_id);

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
  public function breed(FormulaInterface $formula, string $interface): object {

    if ($formula instanceof $interface) {
      return $formula;
    }

    $partials = $this->formulaTypeAndTargetTypeGetPartials(
      \get_class($formula),
      $interface);

    $candidate = NULL;
    foreach ($partials as $partial) {

      $candidate = $partial->breed($formula, $interface, $this);

      if ($candidate === NULL) {
        continue;
      }

      if ($candidate instanceof $interface) {
        return $candidate;
      }

      // Fall through to the runtime exception below.
      break;
    }

    $replacements = [
      '@formula_class' => get_class($formula),
      '@interface' => $interface,
      '@found' => MessageUtil::formatValue($candidate),
    ];

    if ($candidate === NULL) {
      throw new IncarnatorException(strtr(
        'Unsupported formula of class @formula_class: Expected @interface object, found @found.',
        $replacements));
    }

    throw new \RuntimeException(strtr(
      'Misbehaving FTA for formula of class @formula_class: Expected @interface object, found @found.',
      $replacements));
  }

  /**
   * @param string $formulaType
   * @param string $targetType
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   */
  private function formulaTypeAndTargetTypeGetPartials(string $formulaType, string $targetType): array {

    return $this->partialsGrouped[$formulaType][$targetType]
      ?? ($this->partialsGrouped[$formulaType][$targetType] = $this->formulaTypeAndTargetTypeCollectPartials(
        $formulaType,
        $targetType));
  }

  /**
   * @param string $formulaType
   * @param string $targetType
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   */
  private function formulaTypeAndTargetTypeCollectPartials(string $formulaType, string $targetType): array {

    return $this->partialsGroupedReverse[$targetType][$formulaType] = array_intersect_key(
      $this->formulaTypeGetPartials($formulaType),
      $this->targetTypeGetPartials($targetType));
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   */
  private function targetTypeGetPartials(string $interface): array {

    return $this->partialsByTargetType[$interface]
      ?? $this->partialsByTargetType[$interface] = $this->targetTypeCollectPartials($interface);
  }

  /**
   * @param string $targetType
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   */
  private function targetTypeCollectPartials(string $targetType): array {

    $partials = [];
    /** @var \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface $partial */
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
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   */
  private function formulaTypeGetPartials(string $interface): array {

    return $this->partialsByFormulaType[$interface]
      ?? $this->partialsByFormulaType[$interface] = $this->formulaTypeCollectPartials($interface);
  }

  /**
   * @param string $formulaType
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   */
  private function formulaTypeCollectPartials(string $formulaType): array {

    $partials = [];
    /** @var \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->acceptsFormulaClass($formulaType)) {
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
    return [] !== $this->formulaTypeGetPartials($interface);
  }
}
