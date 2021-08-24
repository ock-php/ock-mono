<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Select\Flat;

use Donquixote\ObCK\Text\TextInterface;

interface FlatSelectBuilderInterface {

  /**
   * Adds a select option.
   *
   * @param string $name
   * @param \Donquixote\ObCK\Text\TextInterface $label
   *
   * @return $this
   */
  public function add(string $name, TextInterface $label): self;

  /**
   * Builds the formula.
   *
   * @return \Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface
   */
  public function create(): Formula_FlatSelectInterface;

}
