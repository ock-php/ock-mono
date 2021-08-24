<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Select\Flat;

use Donquixote\ObCK\Text\TextInterface;

class Formula_FlatSelect_Fixed implements Formula_FlatSelectInterface, FlatSelectBuilderInterface {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private $options;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface[] $options
   */
  public function __construct(array $options) {
    $this->options = $options;
  }

  /**
   * {@inheritdoc}
   */
  public function add(string $name, TextInterface $label): FlatSelectBuilderInterface {
    $this->options[$name] = $label;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function create(): Formula_FlatSelectInterface {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions(): array {
    return $this->options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    return $this->options[$id] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return isset($this->options[$id]);
  }

}
