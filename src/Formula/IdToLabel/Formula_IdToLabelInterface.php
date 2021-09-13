<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\IdToLabel;

use Donquixote\Ock\Formula\Id\Formula_IdInterface;
use Donquixote\Ock\Text\TextInterface;

interface Formula_IdToLabelInterface extends Formula_IdInterface {

  /**
   * Gets the option label for a given id.
   *
   * @param string|int $id
   *   The id.
   *
   * @return \Donquixote\Ock\Text\TextInterface|null
   *   The label as a string or stringable object.
   */
  public function idGetLabel($id): ?TextInterface;

}
