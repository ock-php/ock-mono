<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Select\Flat;

use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;
use Donquixote\Cf\Text\TextInterface;

/**
 * @todo Maybe "Options" should be renamed to "Choice"?
 */
interface CfSchema_FlatSelectInterface extends CfSchema_IdInterface {

  /**
   * @return \Donquixote\Cf\Text\TextInterface[]
   *   Format: $[$optionKey] = $optionLabel
   */
  public function getOptions(): array;

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface;

}
