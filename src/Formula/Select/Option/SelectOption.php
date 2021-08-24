<?php

namespace Donquixote\ObCK\Formula\Select\Option;

use Donquixote\ObCK\Text\TextInterface;

class SelectOption implements SelectOptionInterface {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface|null
   */
  private $label;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface|null
   */
  private $groupLabel;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface|null $label
   * @param \Donquixote\ObCK\Text\TextInterface|null $groupLabel
   */
  public function __construct(?TextInterface $label, ?TextInterface $groupLabel) {
    $this->label = $label;
    $this->groupLabel = $groupLabel;
  }

  public function getLabel(): ?TextInterface {
    return $this->label;
  }

  public function getGroupLabel(): ?TextInterface {
    return $this->groupLabel;
  }

}
