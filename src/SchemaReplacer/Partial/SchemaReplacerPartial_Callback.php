<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\ParamToLabel\ParamToLabel;
use Donquixote\Cf\ParamToLabel\ParamToLabelInterface;
use Donquixote\Cf\Schema\Callback\CfSchema_CallbackInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Schema\Label\CfSchema_Label;
use Donquixote\Cf\Schema\Optional\CfSchema_Optional;
use Donquixote\Cf\Schema\Optional\CfSchema_Optional_Null;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_Callback;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_CallbackMono;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Psr\Log\LoggerInterface;

class SchemaReplacerPartial_Callback implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\Cf\ParamToLabel\ParamToLabelInterface
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
   * @param \Donquixote\Cf\ParamToLabel\ParamToLabelInterface $paramToLabel
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
    return CfSchema_CallbackInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(CfSchemaInterface $original, SchemaReplacerInterface $replacer): ?CfSchemaInterface {

    if (!$original instanceof CfSchema_CallbackInterface) {
      return NULL;
    }

    $callback = $original->getCallback();
    $params = $callback->getReflectionParameters();

    if ([] === $params) {
      return new CfSchema_ValueProvider_Callback($callback);
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
      $replacement = CfSchema_ValueToValue_CallbackMono::create(
        $paramSchemas[0],
        $callback);
      $replacement = new CfSchema_Label($replacement, $paramLabels[0]);
      return $replacement;
    }

    return CfSchema_GroupVal_Callback::create(
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
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface|null
   */
  private function paramGetSchema(
    \ReflectionParameter $param,
    CfContextInterface $context = NULL,
    SchemaReplacerInterface $replacer
  ): ?CfSchemaInterface {

    if (NULL === $reflClassLike = $param->getClass()) {
      return NULL;
    }

    $schema = new CfSchema_IfaceWithContext(
      $reflClassLike->getName(),
      $context);

    $schema = $replacer->schemaGetReplacement($schema);

    if (!$param->isOptional()) {
      return $schema;
    }

    if (NULL === $default = $param->getDefaultValue()) {
      return new CfSchema_Optional_Null($schema);
    }

    $schema = new CfSchema_Optional($schema);

    return $schema->withEmptyValue(
      $default,
      $param->getDefaultValueConstantName());
  }
}
