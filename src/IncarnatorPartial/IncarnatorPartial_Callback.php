<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Ock\Core\Formula\Base\FormulaBaseInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\ReflectionUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class IncarnatorPartial_Callback extends IncarnatorPartialBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialBase|null
   */
  public static function create(
    CallbackReflectionInterface $callback,
    ParamToValueInterface $paramToValue
  ): ?IncarnatorPartialBase {

    $params = $callback->getReflectionParameters();

    if (0
      || !isset($params[0])
      || NULL === ($t0 = $params[0]->getClass())
    ) {
      return NULL;
    }
    unset($params[0]);

    if (FormulaInterface::class === $formulaType = $t0->getName()) {
      $formulaType = NULL;
      $specifity = -1;
    }
    elseif (!is_a($formulaType, FormulaBaseInterface::class, TRUE)) {
      return NULL;
    }
    else {
      $specifity = \count($t0->getInterfaceNames());
    }

    if (1
      && isset($params[1])
      && NULL !== ($t1 = $params[1]->getClass())
      && is_a(IncarnatorInterface::class, $t1->getName(), TRUE)
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
        $formulaType);
    }
    else {
      $sta = new IncarnatorPartial_CallbackNoHelper(
        $callback,
        $formulaType);
    }

    $sta = $sta->withSpecifity($specifity);

    return $sta;
  }

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $formulaType
   * @param string|null $resultType
   */
  public function __construct(
    CallbackReflectionInterface $callback,
    $formulaType = NULL,
    $resultType = NULL
  ) {
    $this->callback = $callback;
    parent::__construct($formulaType, $resultType);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaDoGetObject(
    FormulaInterface $formula,
    string $interface,
    IncarnatorInterface $incarnator
  ): ?object {

    try {
      // Other arguments, e.g. services, might already be part of the callback.
      return $this->callback->invokeArgs([$formula, $incarnator]);
    }
    catch (\Exception $e) {
      throw new IncarnatorException($e->getMessage(), 0, $e);
    }
  }

}
