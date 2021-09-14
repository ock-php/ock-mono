<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Context\CfContextInterface;

class Incarnator_ContextProvidingDecorator extends Incarnator_DecoratorBase {

  /**
   * @var \Donquixote\Ock\Context\CfContextInterface|null
   */
  private $context;

  /**
   * Immutable setter. Sets a context.
   *
   * @param \Donquixote\Ock\Context\CfContextInterface|null $context
   *   Context to constrain available options.
   *
   * @return static
   */
  public function withContext(?CfContextInterface $context): self {
    $instance = clone $this;
    $instance->context = $context;
    return $instance;
  }

  /**
   * @return \Donquixote\Ock\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface {
    return $this->context;
  }

}
