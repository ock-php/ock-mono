<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\LocalPackageUtil;
use Donquixote\OCUI\Util\MessageUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class FormulaToAnythingPartial_SmartChain implements FormulaToAnythingPartialInterface {

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[][][]
   *   Format: $[$formulaType][$targetType] = $partials
   */
  private $partialsGrouped = [];

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[][]
   *   Format: $[$targetType] = $partials
   */
  private $partialsByTargetType = [];

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[][]
   *   Format: $[$formulaType] = $partials
   */
  private $partialsByFormulaType = [];

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   *
   * @throws \Donquixote\OCUI\Exception\STABuilderException
   */
  public static function create(ParamToValueInterface $paramToValue): FormulaToAnythingPartial_SmartChain {
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
  }

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param string $interface
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public function formula(
    FormulaInterface $formula,
    string $interface,
    FormulaToAnythingInterface $helper
  ): ?object {

    $partials = $this->formulaTypeAndTargetTypeGetPartials(
      \get_class($formula),
      $interface);

    $candidate = NULL;
    foreach ($partials as $partial) {
      $candidate = $partial->formula($formula, $interface, $helper);
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
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
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
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  private function formulaTypeAndTargetTypeCollectPartials(string $formulaType, string $targetType): array {

    return array_intersect_key(
      $this->formulaTypeGetPartials($formulaType),
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
  private function formulaTypeGetPartials(string $interface): array {

    return $this->partialsByFormulaType[$interface]
      ?? $this->partialsByFormulaType[$interface] = $this->formulaTypeCollectPartials($interface);
  }

  /**
   * @param string $formulaType
   *
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  private function formulaTypeCollectPartials(string $formulaType): array {

    $partials = [];
    /** @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface $partial */
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
