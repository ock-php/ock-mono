<?php

declare(strict_types = 1);

namespace Donquixote\DID\ClassToCTV;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\DID\ContainerToValue\ContainerToValue_Construct;
use Donquixote\DID\ContainerToValue\ContainerToValueInterface;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;

#[Service]
class ClassToCTV_Construct implements ClassToCTVInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ParamToCTV\ParamToCTVInterface $paramToCTV
   */
  public function __construct(
    #[GetService]
    private readonly ParamToCTVInterface $paramToCTV,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function classGetCTV(\ReflectionClass $reflectionClass): ContainerToValueInterface {
    $argCTVs = [];
    foreach ($reflectionClass->getConstructor()?->getParameters() ?? [] as $parameter) {
      $argCTVs[] = $this->paramToCTV->paramGetCTV($parameter);
    }
    return new ContainerToValue_Construct(
      $reflectionClass->getName(),
      $argCTVs,
    );
  }

}
