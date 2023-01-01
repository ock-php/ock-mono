<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\DID\ContainerToValue\ContainerToValueInterface;

class ParamToCTV_Chain implements ParamToCTVInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ParamToCTV\ParamToCTVInterface[] $p2CTVs
   */
  public function __construct(
    private readonly array $p2CTVs,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?ContainerToValueInterface {
    foreach ($this->p2CTVs as $paramToCTV) {
      $ctv = $paramToCTV->paramGetCTV($parameter);
      if ($ctv !== NULL) {
        return $ctv;
      }
    }
    return NULL;
  }

}
