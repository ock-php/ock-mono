<?php
declare(strict_types=1);

namespace Donquixote\Ock\Reflection\CallbackBinder;

use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;
use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

class CallbackBinder implements CallbackBinderInterface {

  /**
   * @var \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface
   */
  private $paramToValue;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   */
  public function __construct(ParamToValueInterface $paramToValue) {
    $this->paramToValue = $paramToValue;
  }

  /**
   * {@inheritdoc}
   */
  public function bind(CallbackReflectionInterface $callback, array $freeParams = []): ?CallbackReflectionInterface {

    $else = new \stdClass();

    $boundArgs = [];
    $boundArgsPhp = [];
    foreach ($callback->getReflectionParameters() as $i => $param) {
      if (isset($freeParams[$i])) {
        continue;
      }
      if ($else === $argValue = $this->paramToValue->paramGetValue($param, $else)) {
        return NULL;
      }
      $boundArgs[$i] = $argValue;
      // The PHP part is optional.
      if (NULL !== $argPhp = $this->paramToValue->paramGetPhp($param)) {
        $boundArgsPhp[$i] = $argPhp;
      }
    }

    if ([] === $boundArgs) {
      return $callback;
    }

    return new CallbackReflection_BoundParameters(
      $callback,
      $boundArgs,
      $boundArgsPhp);
  }

}
