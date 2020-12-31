<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Boolean;

use Donquixote\Cf\Text\TextInterface;

class CfSchema_Boolean implements CfSchema_BooleanInterface {

  /**
   * @var \Donquixote\Cf\Text\TextInterface|null
   */
  private $trueSummary;

  /**
   * @var \Donquixote\Cf\Text\TextInterface|null
   */
  private $falseSummary;

  /**
   * @param \Donquixote\Cf\Text\TextInterface|null $trueSummary
   * @param \Donquixote\Cf\Text\TextInterface|null $falseSummary
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
