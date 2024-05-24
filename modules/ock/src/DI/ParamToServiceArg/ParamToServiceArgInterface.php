<?php

declare(strict_types = 1);

namespace Drupal\ock\DI\ParamToServiceArg;

interface ParamToServiceArgInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return mixed
   */
  public function paramGetServiceArg(\ReflectionParameter $parameter): mixed;

}
