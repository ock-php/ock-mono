<?php

namespace Donquixote\OCUI\Formula\Select\Option;

use Donquixote\OCUI\Text\TextInterface;

class SelectOption implements SelectOptionInterface {

  /**
   * @var \Donquixote\OCUI\Text\TextInterface|null
   */
  private $label;

  /**
   * @var \Donquixote\OCUI\Text\TextInterface|null
   */
  private $groupLabel;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Text\TextInterface|null $label
   * @param \Donquixote\OCUI\Text\TextInterface|null $groupLabel
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
