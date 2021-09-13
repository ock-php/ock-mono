<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Ock\Core\Formula\Base\FormulaBaseInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\FormulaToAnythingException;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Util\ReflectionUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class Incarnator_Callback extends IncarnatorBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Ock\Incarnator\IncarnatorBase|null
   */
  public static function create(
    CallbackReflectionInterface $callback,
    ParamToValueInterface $paramToValue
  ): ?IncarnatorBase {

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
      && is_a(NurseryInterface::class, $t1->getName(), TRUE)
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
      $sta = new Incarnator_CallbackNoHelper(
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
    NurseryInterface $helper
  ): ?object {

    try {
      // Other arguments, e.g. services, might already be part of the callback.
      return $this->callback->invokeArgs([$formula, $helper]);
    }
    catch (\Exception $e) {
      throw new FormulaToAnythingException($e->getMessage(), 0, $e);
    }
  }
}
