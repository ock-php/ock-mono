<?php

declare(strict_types = 1);

namespace Drupal\ock\DI;

use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;
use Ock\DID\ContainerToValue\ContainerToValueInterface;
use Ock\DID\ParamToCTV\ParamToCTVInterface;

/**
 * Resolves parameters.
 *
 * This is registered as a service twice.
 */
#[Service(self::class)]
class ParamToCTV_CustomArgsDecorator implements ParamToCTVInterface {

  /**
   * @var array<string, mixed|ParamToCTVInterface>
   */
  private array $namedArgs = [];

  /**
   * @var array<string, mixed|\Ock\DID\ContainerToValue\ContainerToValueInterface>
   */
  private array $typeArgs = [];

  /**
   * Constructor.
   *
   * @param \Ock\DID\ParamToCTV\ParamToCTVInterface $decorated
   */
  public function __construct(
    #[GetService]
    private readonly ParamToCTVInterface $decorated,
  ) {}

  /**
   * @param array<string, mixed|\Ock\DID\ContainerToValue\ContainerToValueInterface> $args
   *
   * @return static
   */
  public function withNamedArgs(array $args): static {
    $clone = clone $this;
    $clone->namedArgs = $args;
    return $clone;
  }

  /**
   * @param array<string, mixed|\Ock\DID\ContainerToValue\ContainerToValueInterface> $args
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
  public function paramGetCTV(\ReflectionParameter $parameter, mixed $fail = NULL): ?ContainerToValueInterface {
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
    return $this->decorated->paramGetCTV($parameter);
  }

}
