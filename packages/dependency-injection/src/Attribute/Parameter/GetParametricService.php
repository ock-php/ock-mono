<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Attribute\Parameter;

/**
 * @see \Ock\DependencyInjection\Inspector\FactoryInspector_ParametricServiceAttribute
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class GetParametricService {

  /**
   * @var array<int|string|\Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument>
   */
  public readonly array $args;

  /**
   * Constructor.
   *
   * @param string|int|\Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument ...$args
   *   Arguments for free parameters of the service.
   */
  public function __construct(
    string|int|GetParametricArgument ...$args,
  ) {
    $this->args = $args;
  }

}
