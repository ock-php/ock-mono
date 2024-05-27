<?php

declare(strict_types = 1);

namespace Drupal\ock\DI;

use Ock\Egg\Egg\EggInterface;
use Ock\Egg\ParamToEgg\ParamToEggInterface;
use Symfony\Component\DependencyInjection\Attribute\Exclude;

/**
 * Decorator to resolve parameters based on custom arguments tied to a type or
 * a parameter name.
 *
 * For now, don't register this as a service.
 * See https://github.com/symfony/symfony/discussions/57196
 */
#[Exclude]
class ParamToEgg_CustomArgsDecorator implements ParamToEggInterface {

  /**
   * @var array<string, mixed|\Ock\Egg\ParamToEgg\ParamToEggInterface>
   */
  private array $namedArgs = [];

  /**
   * @var array<string, mixed|\Ock\Egg\Egg\EggInterface>
   */
  private array $typeArgs = [];

  /**
   * Constructor.
   *
   * @param \Ock\Egg\ParamToEgg\ParamToEggInterface $decorated
   */
  public function __construct(
    private readonly ParamToEggInterface $decorated,
  ) {}

  /**
   * @param array<string, mixed|\Ock\Egg\Egg\EggInterface> $args
   *
   * @return static
   */
  public function withNamedArgs(array $args): static {
    $clone = clone $this;
    $clone->namedArgs = $args;
    return $clone;
  }

  /**
   * @param array<string, mixed|\Ock\Egg\Egg\EggInterface> $args
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
  public function paramGetEgg(\ReflectionParameter $parameter, mixed $fail = NULL): ?EggInterface {
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
    return $this->decorated->paramGetEgg($parameter);
  }

}
