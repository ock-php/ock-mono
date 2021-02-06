<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaToAnything\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Exception\SchemaToAnythingException;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;

class SchemaToAnythingPartial_CallbackNoHelper extends SchemaToAnythingPartialBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $class
   * @param string|null $schemaType
   *
   * @return self
   */
  public static function fromClassName(string $class, $schemaType = NULL): self {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($class);
    return new self(
      $callback,
      $schemaType,
      $class);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $resultType
   *
   * @return \Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface|null
   */
  public static function create(CallbackReflectionInterface $callback, $resultType = NULL): ?SchemaToAnythingPartialInterface {

    $params = $callback->getReflectionParameters();

    if ([0] !== array_keys($params)) {
      return NULL;
    }

    if (NULL === $t0 = $params[0]->getClass()) {
      return NULL;
    }

    if (CfSchemaInterface::class === $schemaType = $t0->getName()) {
      $schemaType = NULL;
    }
    elseif (!is_a($schemaType, CfSchemaInterface::class, TRUE)) {
      return NULL;
    }

    return new self($callback, $schemaType, $resultType);
  }

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $schemaType
   * @param string|null $resultType
   */
  public function __construct(CallbackReflectionInterface $callback, $schemaType = NULL, $resultType = NULL) {
    $this->callback = $callback;
    parent::__construct($schemaType, $resultType);
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  protected function schemaDoGetObject(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
  ) {

    try {
      return $this->callback->invokeArgs([$schema]);
    }
    catch (\Exception $e) {
      throw new SchemaToAnythingException("Exception in callback.", 0, $e);
    }
  }
}
