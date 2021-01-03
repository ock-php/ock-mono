<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Callback;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;

class CfSchema_Callback implements CfSchema_CallbackInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface[]
   */
  private $explicitSchemas = [];

  /**
   * @var string[]
   */
  private $explicitLabels = [];

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param string $class
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\Callback\CfSchema_Callback
   */
  public static function fromClass(string $class, CfContextInterface $context = NULL): CfSchema_Callback {

    return new self(
      CallbackReflection_ClassConstruction::create($class),
      $context);
  }

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return self
   */
  public static function fromStaticMethod(string $class, string $methodName, CfContextInterface $context = NULL): CfSchema_Callback {

    return new self(
      CallbackReflection_StaticMethod::create(
        $class,
        $methodName),
      $context);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(
    CallbackReflectionInterface $callbackReflection,
    CfContextInterface $context = NULL
  ) {
    $this->callbackReflection = $callbackReflection;
    $this->context = $context;
  }

  /**
   * @param \Donquixote\Cf\Context\CfContextInterface|NULL $context
   *
   * @return static
   */
  public function withContext(CfContextInterface $context = NULL) {
    $clone = clone $this;
    $clone->context = $context;
    return $clone;
  }

  /**
   * @param int $index
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param string|null $label
   *
   * @return static
   */
  public function withParamSchema(int $index, CfSchemaInterface $schema, $label = NULL) {
    $clone = clone $this;
    $clone->explicitSchemas[$index] = $schema;
    if (NULL !== $label) {
      $clone->explicitLabels[$index] = $label;
    }
    return $clone;
  }

  /**
   * @param int $index
   * @param string $label
   *
   * @return static
   */
  public function withParamLabel(int $index, string $label) {
    $clone = clone $this;
    $clone->explicitLabels[$index] = $label;
    return $clone;
  }

  /**
   * @param int $index
   * @param string $interface
   * @param string|null $label
   *
   * @return static
   */
  public function withParam_Iface(int $index, string $interface, $label = NULL) {
    return $this->withParamSchema(
      $index,
      new CfSchema_IfaceWithContext($interface, $this->context),
      $label);
  }

  /**
   * @param int $index
   * @param string $interface
   * @param string|null $label
   *
   * @return static
   */
  public function withParam_IfaceSequence(int $index, string $interface, $label = NULL) {
    return $this->withParamSchema(
      $index,
      CfSchema_IfaceWithContext::createSequence($interface, $this->getContext()),
      $label);
  }

  /**
   * @param int $index
   * @param string $interface
   * @param string|null $label
   *
   * @return static
   */
  public function withParam_IfaceOrNull(int $index, string $interface, $label = NULL) {
    return $this->withParamSchema(
      $index,
      CfSchema_IfaceWithContext::createOptional($interface, $this->getContext()),
      $label);
  }

  /**
   * {@inheritdoc}
   */
  public function getCallback(): CallbackReflectionInterface {
    return $this->callbackReflection;
  }

  /**
   * {@inheritdoc}
   */
  public function getExplicitParamSchemas(): array {
    return $this->explicitSchemas;
  }

  /**
   * {@inheritdoc}
   */
  public function getExplicitParamLabels(): array {
    return $this->explicitLabels;
  }

  /**
   * {@inheritdoc}
   */
  public function getContext(): ?CfContextInterface {
    return $this->context;
  }
}
