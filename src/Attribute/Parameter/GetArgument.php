<?php

declare(strict_types=1);

namespace Donquixote\DID\Attribute\Parameter;

use Donquixote\DID\ValueDefinition\ValueDefinition_GetArgument;

/**
 * Gets an argument from a callable service.
 *
 * @see \Donquixote\DID\Attribute\ParametricService
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class GetArgument implements ServiceArgumentAttributeInterface {

  /**
   * Constructor.
   *
   * @param int $position
   */
  public function __construct(
    private readonly int $position = 0,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getArgumentDefinition(\ReflectionParameter $parameter): mixed {
    return new ValueDefinition_GetArgument($this->position);
  }

}
