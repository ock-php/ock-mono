<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Select\Flat;

use Donquixote\ObCK\Formula\IdToLabel\Formula_IdToLabelInterface;
use Donquixote\ObCK\Text\TextInterface;

/**
 * @todo Maybe "Options" should be renamed to "Choice"?
 */
interface Formula_FlatSelectInterface extends Formula_IdToLabelInterface {

  /**
   * @return \Donquixote\ObCK\Text\TextInterface[]
   *   Format: $[$optionKey] = $optionLabel
   */
  public function getOptions(): array;

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface;

}
