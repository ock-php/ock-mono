<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaToAnything\Partial;

use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;
use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\OCUI\Core\Formula\Base\CfSchemaBaseInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Util\ReflectionUtil;

class SchemaToAnythingPartial_Callback extends SchemaToAnythingPartialBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialBase|null
   */
  public static function create(
    CallbackReflectionInterface $callback,
    ParamToValueInterface $paramToValue
  ): ?SchemaToAnythingPartialBase {

    $params = $callback->getReflectionParameters();

    if (0
      || !isset($params[0])
      || NULL === ($t0 = $params[0]->getClass())
    ) {
      return NULL;
    }
    unset($params[0]);

    if (FormulaInterface::class === $schemaType = $t0->getName()) {
      $schemaType = NULL;
      $specifity = -1;
    }
    elseif (!is_a($schemaType, CfSchemaBaseInterface::class, TRUE)) {
      return NULL;
    }
    else {
      $specifity = \count($t0->getInterfaceNames());
    }

    if (1
      && isset($params[1])
      && NULL !== ($t1 = $params[1]->getClass())
      && is_a(SchemaToAnythingInterface::class, $t1->getName(), TRUE)
    ) {
      $hasStaParam = TRUE;
      unset($params[1]);
    }
    else {
      $hasStaParam = FALSE;
    }

    if ([] !== $params) {
      if (NULL === $boundArgs = ReflectionUtil::paramsGetValues($params, $paramToValue)) {
        return NULL;
      }

      $callback = new CallbackReflection_BoundParameters($callback, $boundArgs);
    }

    if ($hasStaParam) {
      $sta = new self(
        $callback,
        $schemaType);
    }
    else {
      $sta = new SchemaToAnythingPartial_CallbackNoHelper(
        $callback,
        $schemaType);
    }

    $sta = $sta->withSpecifity($specifity);

    return $sta;
  }

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $schemaType
   * @param string|null $resultType
   */
  public function __construct(
    CallbackReflectionInterface $callback,
    $schemaType = NULL,
    $resultType = NULL
  ) {
    $this->callback = $callback;
    parent::__construct($schemaType, $resultType);
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param string $interface
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  protected function schemaDoGetObject(
    FormulaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
  ) {

    try {
      // Other arguments, e.g. services, might already be part of the callback.
      return $this->callback->invokeArgs([$schema, $helper]);
    }
    catch (\Exception $e) {
      // @todo Log exception in callback!
      unset($e);
      return null;
    }
  }
}
