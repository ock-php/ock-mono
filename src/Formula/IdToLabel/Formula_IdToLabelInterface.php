<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\IdToLabel;

use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\Text\TextInterface;

interface Formula_IdToLabelInterface extends Formula_IdInterface {

  /**
   * Gets the option label for a given id.
   *
   * @param string|int $id
   *   The id.
   *
   * @return \Donquixote\ObCK\Text\TextInterface|null
   *   The label as a string or stringable object.
   */
  public function idGetLabel($id): ?TextInterface;

}
