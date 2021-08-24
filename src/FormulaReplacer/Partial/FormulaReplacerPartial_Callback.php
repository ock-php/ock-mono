<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\ParamToLabel\ParamToLabel;
use Donquixote\ObCK\ParamToLabel\ParamToLabelInterface;
use Donquixote\ObCK\Formula\Callback\Formula_CallbackInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\ObCK\Formula\Iface\Formula_Iface;
use Donquixote\ObCK\Formula\Label\Formula_Label;
use Donquixote\ObCK\Formula\Optional\Formula_Optional;
use Donquixote\ObCK\Formula\Optional\Formula_Optional_Null;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_Callback;
use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue_CallbackMono;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;
use Psr\Log\LoggerInterface;

class FormulaReplacerPartial_Callback implements FormulaReplacerPartialInterface {

  /**
   * @var \Donquixote\ObCK\ParamToLabel\ParamToLabelInterface
   */
  private $paramToLabel;

  /**
   * @var \Psr\Log\LoggerInterface
   */
  private $logger;

  /**
   * @param \Psr\Log\LoggerInterface $logger
   *
   * @return self
   */
  public static function create(LoggerInterface $logger): FormulaReplacerPartial_Callback {
    return new self(new ParamToLabel(), $logger);
  }

  /**
   * @param \Donquixote\ObCK\ParamToLabel\ParamToLabelInterface $paramToLabel
   * @param \Psr\Log\LoggerInterface $logger
   */
  public function __construct(ParamToLabelInterface $paramToLabel, LoggerInterface $logger) {
    $this->paramToLabel = $paramToLabel;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceFormulaClass(): string {
    return Formula_CallbackInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function formulaGetReplacement(FormulaInterface $original, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$original instanceof Formula_CallbackInterface) {
      return NULL;
    }

    $callback = $original->getCallback();
    $params = $callback->getReflectionParameters();

    if ([] === $params) {
      return new Formula_ValueProvider_Callback($callback);
    }

    $explicitParamFormulas = $original->getExplicitParamFormulas();
    $explicitParamLabels = $original->getExplicitParamLabels();
    $context = $original->getContext();

    $paramFormulas = [];
    $paramLabels = [];
    foreach ($params as $i => $param) {

      if (isset($explicitParamFormulas[$i])) {
        $paramFormulas[] = $explicitParamFormulas[$i];
      }
      elseif ($paramFormula = $this->paramGetFormula($param, $context, $replacer)) {
        $paramFormulas[] = $paramFormula;
      }
      elseif ($param->isOptional()) {
        break;
      }
      else {
        // The callback has parameters that cannot be made configurable.
        $this->logUnconfigurableParameter($callback, $i);

        return NULL;
      }

      if (isset($explicitParamLabels[$i])) {
        $paramLabels[] = $explicitParamLabels[$i];
      }
      else {
        $paramLabels[] = $this->paramToLabel->paramGetLabel($param);
      }
    }

    if (1 === \count($paramFormulas)) {
      $replacement = Formula_ValueToValue_CallbackMono::create(
        $paramFormulas[0],
        $callback);
      $replacement = new Formula_Label($replacement, $paramLabels[0]);
      return $replacement;
    }

    return Formula_GroupVal_Callback::create(
      $callback,
      $paramFormulas,
      $paramLabels);
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
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   * @param \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  private function paramGetFormula(
    \ReflectionParameter $param,
    CfContextInterface $context = NULL,
    FormulaReplacerInterface $replacer
  ): ?FormulaInterface {

    if (NULL === $reflClassLike = $param->getClass()) {
      return NULL;
    }

    $formula = new Formula_Iface(
      $reflClassLike->getName(),
      $context);

    $formula = $replacer->formulaGetReplacement($formula);

    if ($param->allowsNull()) {
      return new Formula_Optional_Null($formula);
    }

    if (!$param->isOptional()) {
      return $formula;
    }

    $formula = new Formula_Optional($formula);

    try {
      $emptyPhp = $param->getDefaultValueConstantName();
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException('Impossible exception.');
    }

    return $formula->withEmptyPhp($emptyPhp);
  }
}
