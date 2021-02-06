<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Select\Flat;

use Donquixote\OCUI\Schema\Id\CfSchema_IdInterface;
use Donquixote\OCUI\Text\TextInterface;

/**
 * @todo Maybe "Options" should be renamed to "Choice"?
 */
interface CfSchema_FlatSelectInterface extends CfSchema_IdInterface {

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
