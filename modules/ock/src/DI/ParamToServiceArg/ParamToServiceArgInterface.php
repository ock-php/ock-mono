<?php

declare(strict_types = 1);

namespace Drupal\ock\DI\ParamToServiceArg;

interface ParamToServiceArgInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return mixed
   *
   * @throws \Ock\DID\Exception\DiscoveryException
   */
  public function paramGetServiceArg(\ReflectionParameter $parameter): mixed;

}
