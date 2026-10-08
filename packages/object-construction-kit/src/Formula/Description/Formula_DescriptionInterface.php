<?php

declare(strict_types=1);

namespace Ock\Ock\Formula\Description;

use Ock\Text\TextInterface;

interface Formula_DescriptionInterface {

  /**
   * @return \Ock\Text\TextInterface|null
   */
  public function getDescription(): ?TextInterface;

}
