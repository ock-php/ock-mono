<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select\Flat;

use Donquixote\OCUI\Text\TextInterface;

interface FlatSelectBuilderInterface {

  /**
   * Adds a select option.
   *
   * @param string $name
   * @param \Donquixote\OCUI\Text\TextInterface $label
   *
   * @return $this
   */
  public function add(string $name, TextInterface $label): self;

  /**
   * Builds the formula.
   *
   * @return \Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface
   */
  public function create(): Formula_FlatSelectInterface;

}
