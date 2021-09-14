<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Boolean;

use Donquixote\Ock\Text\TextInterface;

class Formula_Boolean implements Formula_BooleanInterface {

  /**
   * @var \Donquixote\Ock\Text\TextInterface|null
   */
  private $trueSummary;

  /**
   * @var \Donquixote\Ock\Text\TextInterface|null
   */
  private $falseSummary;

  /**
   * @param \Donquixote\Ock\Text\TextInterface|null $trueSummary
   * @param \Donquixote\Ock\Text\TextInterface|null $falseSummary
   */
  public function __construct(TextInterface $trueSummary, TextInterface $falseSummary) {
    $this->trueSummary = $trueSummary;
    $this->falseSummary = $falseSummary;
  }

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
