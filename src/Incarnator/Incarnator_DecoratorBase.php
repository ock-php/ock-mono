<?php

declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;

class Incarnator_DecoratorBase implements IncarnatorInterface {

  /**
   * @var \Donquixote\Ock\Incarnator\IncarnatorInterface
   */
  private IncarnatorInterface $decorated;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $decorated
   */
  public function __construct(IncarnatorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function incarnate(FormulaInterface $formula, string $interface, IncarnatorInterface $incarnator): object {
    return $this->decorated->incarnate($formula, $interface, $incarnator);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheId(): string {
    return $this->decorated->getCacheId();
  }

}
