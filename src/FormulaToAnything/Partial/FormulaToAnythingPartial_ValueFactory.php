<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaToAnything\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Formula\Iface\Formula_Iface;
use Donquixote\ObCK\Formula\ValueFactory\Formula_ValueFactoryInterface;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_Callback;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\ParamToLabel\ParamToLabelInterface;
use Donquixote\ObCK\Text\Text;
use Psr\Log\LoggerInterface;

/**
 * @STA
 */
class FormulaToAnythingPartial_ValueFactory extends FormulaToAnythingPartial_FormulaReplacerBase {

  /**
   * @var \Donquixote\ObCK\ParamToLabel\ParamToLabelInterface
   */
  private $paramToLabel;

  /**
   * @var \Psr\Log\LoggerInterface
   */
  private $logger;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\ParamToLabel\ParamToLabelInterface $paramToLabel
   * @param \Psr\Log\LoggerInterface $logger
   */
  public function __construct(ParamToLabelInterface $paramToLabel, LoggerInterface $logger) {
    parent::__construct(Formula_ValueFactoryInterface::class);
    $this->paramToLabel = $paramToLabel;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, FormulaToAnythingInterface $helper): ?FormulaInterface {

    /** @var \Donquixote\ObCK\Formula\ValueFactory\Formula_ValueFactoryInterface $formula */

    $factory = $formula->getValueFactory();
    $params = $factory->getReflectionParameters();

    if ([] === $params) {
      return new Formula_ValueProvider_Callback($factory);
    }

    $builder = Formula::group();
    foreach ($params as $i => $param) {
      $name = $param->getName();
      $param_formula = $this->paramGetFormula($param);
      if (!$param_formula) {
        if ($param->isOptional()) {
          // Ignore further parameters.
          break;
        }

        // The callback has parameters that cannot be made configurable.
        $this->logUnconfigurableParameter(
          $factory,
          $i);

        return NULL;
      }

      $param_label = $this->paramToLabel->paramGetLabel($param);

      $builder->add(
        $name,
        $param_formula,
        Text::t($param_label ?? $name));
    }

    return $builder->createWithCallback($factory);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param int $iParamUnconfigurable
   *   Parameter index which is creating problems.
   */
  private function logUnconfigurableParameter(CallbackReflectionInterface $callback, int $iParamUnconfigurable): void {

    $params = $callback->getReflectionParameters();

    $badParam = $params[$iParamUnconfigurable];

    $argsPhp = [];
    foreach ($params as $i => $param) {
      $argsPhp[$i] = '$' . $param->getName();
    }

    $callPhp = $callback->argsPhpGetPhp($argsPhp, new CodegenHelper());

    $this->logger->warning(
      'Parameter {param} of {callback} is not configurable.',
      [
        'param' => '$' . $badParam->getName(),
        'callback' => $callPhp,
      ]);
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  private function paramGetFormula(\ReflectionParameter $param): ?FormulaInterface {

    if (NULL === $reflClassLike = $param->getClass()) {
      return NULL;
    }

    $formula = new Formula_Iface(
      $reflClassLike->getName(),
      $param->allowsNull());

    if (!$param->isOptional()) {
      return $formula;
    }

    try {
      $default = $param->getDefaultValue();
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException('Impossible exception.');
    }

    if ($default === NULL) {
      return $formula;
    }

    // Unsupported default value.
    // @todo Log this.
    return NULL;
  }

}
