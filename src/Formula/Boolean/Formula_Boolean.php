<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Boolean;

use Donquixote\Ock\Text\TextInterface;

class Formula_Boolean implements Formula_BooleanInterface {

  /**
   * @param \Donquixote\Ock\Text\TextInterface|null $trueSummary
   * @param \Donquixote\Ock\Text\TextInterface|null $falseSummary
   */
  public function __construct(
    private readonly ?TextInterface $trueSummary,
    private readonly ?TextInterface $falseSummary,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getTrueSummary(): ?TextInterface {
    return $this->trueSummary;
  }

  /**
   * {@inheritdoc}
   */
  public function getFalseSummary(): ?TextInterface {
    return $this->falseSummary;
  }

}
