<?php

namespace Donquixote\Ock\Formula\Select\Option;

use Donquixote\Ock\Text\TextInterface;

class SelectOption implements SelectOptionInterface {

  /**
   * @var \Donquixote\Ock\Text\TextInterface|null
   */
  private $label;

  /**
   * @var \Donquixote\Ock\Text\TextInterface|null
   */
  private $groupLabel;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Text\TextInterface|null $label
   * @param \Donquixote\Ock\Text\TextInterface|null $groupLabel
   */
  public function __construct(?TextInterface $label, ?TextInterface $groupLabel) {
    $this->label = $label;
    $this->groupLabel = $groupLabel;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel(): ?TextInterface {
    return $this->label;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupLabel(): ?TextInterface {
    return $this->groupLabel;
  }

}
