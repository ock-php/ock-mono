<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select\Flat;

use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\Text\TextInterface;

/**
 * @todo Maybe "Options" should be renamed to "Choice"?
 */
interface Formula_FlatSelectInterface extends Formula_IdInterface {

  /**
   * @return \Donquixote\OCUI\Text\TextInterface[]
   *   Format: $[$optionKey] = $optionLabel
   */
  public function getOptions(): array;

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface;

}
