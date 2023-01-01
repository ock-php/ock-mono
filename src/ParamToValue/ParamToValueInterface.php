<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToValue;

interface ParamToValueInterface {

  /**
   * @param \ReflectionParameter $parameter
   * @param mixed|null $fail
   *   Unique value to return if no value can be obtained.
   *   If this is NULL, an exception will be thrown instead.
   *
   * @return mixed
   *   Argument value suitable for the parameter, or $fail if it was not NULL.
   *
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   *   Cannot resolve parameter, and $fail was NULL.
   */
  public function paramGetValue(\ReflectionParameter $parameter, mixed $fail = NULL): mixed;

}
