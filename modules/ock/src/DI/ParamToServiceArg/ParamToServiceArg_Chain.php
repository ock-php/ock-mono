<?php

declare(strict_types = 1);

namespace Drupal\ock\DI\ParamToServiceArg;

class ParamToServiceArg_Chain implements ParamToServiceArgInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\ock\DI\ParamToServiceArg\ParamToServiceArgInterface[] $ptsas
   */
  public function __construct(
    private readonly array $ptsas,
  ) {}

  /**
   * @inheritDoc
   */
  public function paramGetServiceArg(\ReflectionParameter $parameter): mixed {
    foreach ($this->ptsas as $paramToServiceArg) {
      $ctv = $paramToServiceArg->paramGetServiceArg($parameter);
      if ($ctv !== NULL) {
        return $ctv;
      }
    }
    return NULL;
  }

}
