<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Util\LocalPackageUtil;
use Donquixote\Ock\Util\MessageUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class Incarnator_SmartChain extends IncarnatorZeroBase {

  /**
   * @var \Donquixote\Ock\Incarnator\IncarnatorInterface[][][]
   *   Format: $[$formulaType][$targetType] = $partials
   */
  private $partialsGrouped = [];

  /**
   * @var \Donquixote\Ock\Incarnator\IncarnatorInterface[][]
   *   Format: $[$targetType] = $partials
   */
  private $partialsByTargetType = [];

  /**
   * @var \Donquixote\Ock\Incarnator\IncarnatorInterface[][]
   *   Format: $[$formulaType] = $partials
   */
  private $partialsByFormulaType = [];

  /**
   * @var \Donquixote\Ock\Incarnator\IncarnatorInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   *
   * @throws \Donquixote\Ock\Exception\STABuilderException
   */
  public static function create(ParamToValueInterface $paramToValue): Incarnator_SmartChain {
    $partials = LocalPackageUtil::collectIncarnators($paramToValue);
    return new self($partials);
  }

  /**
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface[] $partials
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
  public function breed(
    FormulaInterface $formula,
    string $interface,
    NurseryInterface $nursery
  ): ?object {

    $partials = $this->formulaTypeAndTargetTypeGetPartials(
      \get_class($formula),
      $interface);

    $candidate = NULL;
    foreach ($partials as $partial) {
      $candidate = $partial->breed($formula, $interface, $nursery);
      if ($candidate instanceof $interface) {
        return $candidate;
      }
      if ($candidate !== NULL) {
        // Misbehaving FTA.
        // Fall through to the runtime exception below.
        break;
      }
    }

    if ($candidate === NULL) {
      return NULL;
    }

    $replacements = [
      '@formula_class' => get_class($formula),
      '@interface' => $interface,
      '@found' => MessageUtil::formatValue($candidate),
    ];

    throw new \RuntimeException(strtr(
      'Misbehaving FTA for formula of class @formula_class: Expected @interface object, found @found.',
      $replacements));
  }

  /**
   * @param string $formulaType
   * @param string $targetType
   *
   * @return \Donquixote\Ock\Incarnator\IncarnatorInterface[]
   */
  private function formulaTypeAndTargetTypeGetPartials(string $formulaType, string $targetType): array {

    return $this->partialsGrouped[$formulaType][$targetType]
      ??= $this->formulaTypeAndTargetTypeCollectPartials(
        $formulaType,
        $targetType);
  }

  /**
   * @param string $formulaType
   * @param string $targetType
   *
   * @return \Donquixote\Ock\Incarnator\IncarnatorInterface[]
   */
  private function formulaTypeAndTargetTypeCollectPartials(string $formulaType, string $targetType): array {

    return array_intersect_key(
      $this->formulaTypeGetPartials($formulaType),
      $this->targetTypeGetPartials($targetType));
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Ock\Incarnator\IncarnatorInterface[]
   */
  private function targetTypeGetPartials(string $interface): array {

    return $this->partialsByTargetType[$interface]
      ??= $this->targetTypeCollectPartials($interface);
  }

  /**
   * @param string $targetType
   *
   * @return \Donquixote\Ock\Incarnator\IncarnatorInterface[]
   */
  private function targetTypeCollectPartials(string $targetType): array {

    $partials = [];
    /** @var \Donquixote\Ock\Incarnator\IncarnatorInterface $partial */
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
   * @return \Donquixote\Ock\Incarnator\IncarnatorInterface[]
   */
  private function formulaTypeGetPartials(string $interface): array {

    return $this->partialsByFormulaType[$interface]
      ??= $this->formulaTypeCollectPartials($interface);
  }

  /**
   * @param string $formulaType
   *
   * @return \Donquixote\Ock\Incarnator\IncarnatorInterface[]
   */
  private function formulaTypeCollectPartials(string $formulaType): array {

    $partials = [];
    /** @var \Donquixote\Ock\Incarnator\IncarnatorInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->acceptsFormulaClass($formulaType)) {
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
  public function acceptsFormulaClass(string $formulaClass): bool {
    return [] !== $this->formulaTypeGetPartials($formulaClass);
  }
}
