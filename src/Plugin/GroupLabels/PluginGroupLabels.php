<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\GroupLabels;

class PluginGroupLabels implements PluginGroupLabelsInterface {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private array $labels;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface[] $labels
   */
  public function __construct(array $labels) {
    $this->labels = $labels;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabels(): array {
    return $this->labels;
  }

}
