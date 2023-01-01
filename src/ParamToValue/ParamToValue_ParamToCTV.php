<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToValue;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\DID\Exception\ContainerToValueException;
use Donquixote\DID\Exception\DiscoveryException;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Donquixote\DID\Util\MessageUtil;
use Psr\Container\ContainerInterface;

#[Service]
class ParamToValue_ParamToCTV implements ParamToValueInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ParamToCTV\ParamToCTVInterface $paramToCTV
   * @param \Psr\Container\ContainerInterface $container
   */
  public function __construct(
    #[GetService]
    private readonly ParamToCTVInterface $paramToCTV,
    #[GetService]
    private readonly ContainerInterface $container,
  ) {}

  /**
   * @inheritDoc
   */
  public function paramGetValue(\ReflectionParameter $parameter, mixed $fail = NULL): mixed {
    try {
      $ctv = $this->paramToCTV->paramGetCTV($parameter);
    }
    catch (DiscoveryException $e) {
      throw new ContainerToValueException($e->getMessage(), 0, $e);
    }
    if ($ctv !== NULL) {
      return $ctv->containerGetValue($this->container);
    }
    if ($fail === NULL) {
      throw new ContainerToValueException(sprintf(
        'Cannot resolve %s.',
        MessageUtil::formatReflector($parameter),
      ));
    }
    return $fail;
  }

}
