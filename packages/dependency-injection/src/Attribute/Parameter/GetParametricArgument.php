<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Attribute\Parameter;

/**
 * Wires a parametric argument to the parameter.
 *
 * The TARGET_PROPERTY is to avoid error when used on promoted properties.
 *
 * @see \Ock\DependencyInjection\Inspector\FactoryInspector_ParametricServiceAttribute
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class GetParametricArgument {

  public function __construct(
    public readonly int|string|null $delta = null,
  ) {}

}
