<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToValue;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;

/**
 * Resolves parameters.
 *
 * This is registered as a service twice.
 */
#[Service(self::class)]
class ParamToValue_CustomArgsDecorator implements ParamToValueInterface {

  /**
   * @var array<string, mixed>
   */
  private array $namedArgs = [];

  /**
   * @var array<string, mixed>
   */
  private array $typeArgs = [];

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ParamToValue\ParamToValueInterface $decorated
   */
  public function __construct(
    #[GetService]
    private readonly ParamToValueInterface $decorated,
  ) {}

  /**
   * @param array<string, mixed> $args
   *
   * @return static
   */
  public function withNamedArgs(array $args): static {
    $clone = clone $this;
    $clone->namedArgs = $args;
    return $clone;
  }

  /**
   * @param array<string, mixed> $args
   *
   * @return static
   */
  public function withTypeArgs(array $args): static {
    $clone = clone $this;
    $clone->typeArgs = $args;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function paramGetValue(\ReflectionParameter $parameter, mixed $fail = NULL): mixed {
    if ($this->namedArgs) {
      $name = $parameter->getName();
      if (array_key_exists($name, $this->namedArgs)) {
        return $this->namedArgs[$name];
      }
    }
    if ($this->typeArgs) {
      $rType = $parameter->getType();
      if ($rType instanceof \ReflectionNamedType) {
        $typeName = $rType->getName();
        if (array_key_exists($typeName, $this->typeArgs)) {
          return $this->typeArgs[$typeName];
        }
      }
    }
    return $this->decorated->paramGetValue($parameter, $fail);
  }

}
