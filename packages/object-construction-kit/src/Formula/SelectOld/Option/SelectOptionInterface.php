<?php

declare(strict_types=1);

namespace Ock\Ock\Formula\SelectOld\Option;

use Ock\Text\TextInterface;

interface SelectOptionInterface {

  /**
   * @return \Ock\Text\TextInterface|null
   */
  public function getLabel(): ?TextInterface;

  /**
   * @return \Ock\Text\TextInterface|null
   */
  public function getGroupLabel(): ?TextInterface;

}
