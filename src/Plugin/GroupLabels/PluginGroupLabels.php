<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\GroupLabels;

class PluginGroupLabels implements PluginGroupLabelsInterface {

  /**
   * @var \Donquixote\Ock\Text\TextInterface[]
   */
  private array $labels;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Text\TextInterface[] $labels
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
