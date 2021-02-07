<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\ParamToLabel\ParamToLabel;
use Donquixote\OCUI\ParamToLabel\ParamToLabelInterface;
use Donquixote\OCUI\Formula\Callback\Formula_CallbackInterface;
use Donquixote\OCUI\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContext;
use Donquixote\OCUI\Formula\Label\Formula_Label;
use Donquixote\OCUI\Formula\Optional\Formula_Optional;
use Donquixote\OCUI\Formula\Optional\Formula_Optional_Null;
use Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProvider_Callback;
use Donquixote\OCUI\Formula\ValueToValue\Formula_ValueToValue_CallbackMono;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;
use Psr\Log\LoggerInterface;

class SchemaReplacerPartial_Callback implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\OCUI\ParamToLabel\ParamToLabelInterface
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
  public static function create(LoggerInterface $logger): SchemaReplacerPartial_Callback {
    return new self(new ParamToLabel(), $logger);
  }

  /**
   * @param \Donquixote\OCUI\ParamToLabel\ParamToLabelInterface $paramToLabel
   * @param \Psr\Log\LoggerInterface $logger
   */
  public function __construct(ParamToLabelInterface $paramToLabel, LoggerInterface $logger) {
    $this->paramToLabel = $paramToLabel;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceSchemaClass(): string {
    return Formula_CallbackInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(FormulaInterface $original, SchemaReplacerInterface $replacer): ?FormulaInterface {

    if (!$original instanceof Formula_CallbackInterface) {
      return NULL;
    }

    $callback = $original->getCallback();
    $params = $callback->getReflectionParameters();

    if ([] === $params) {
      return new Formula_ValueProvider_Callback($callback);
    }

    $explicitParamSchemas = $original->getExplicitParamSchemas();
    $explicitParamLabels = $original->getExplicitParamLabels();
    $context = $original->getContext();

    $paramSchemas = [];
    $paramLabels = [];
    foreach ($params as $i => $param) {

      if (isset($explicitParamSchemas[$i])) {
        $paramSchemas[] = $explicitParamSchemas[$i];
      }
      elseif ($paramSchema = $this->paramGetSchema($param, $context, $replacer)) {
        $paramSchemas[] = $paramSchema;
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

    if (1 === \count($paramSchemas)) {
      $replacement = Formula_ValueToValue_CallbackMono::create(
        $paramSchemas[0],
        $callback);
      $replacement = new Formula_Label($replacement, $paramLabels[0]);
      return $replacement;
    }

    return Formula_GroupVal_Callback::create(
      $callback,
      $paramSchemas,
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
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  private function paramGetSchema(
    \ReflectionParameter $param,
    CfContextInterface $context = NULL,
    SchemaReplacerInterface $replacer
  ): ?FormulaInterface {

    if (NULL === $reflClassLike = $param->getClass()) {
      return NULL;
    }

    $schema = new Formula_IfaceWithContext(
      $reflClassLike->getName(),
      $context);

    $schema = $replacer->schemaGetReplacement($schema);

    if ($param->allowsNull()) {
      return new Formula_Optional_Null($schema);
    }

    if (!$param->isOptional()) {
      return $schema;
    }

    $schema = new Formula_Optional($schema);

    try {
      $emptyPhp = $param->getDefaultValueConstantName();
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException('Impossible exception.');
    }

    return $schema->withEmptyPhp($emptyPhp);
  }
}
