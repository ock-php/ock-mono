<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Boolean;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Text\TextInterface;

interface CfSchema_BooleanInterface extends CfSchemaInterface {

  /**
   * Gets a summary for true.
   *
   * @return \Donquixote\Cf\Text\TextInterface|null
   *   Summary in case the value is true.
   */
  public function getTrueSummary(): ?TextInterface;

  /**
   * @return \Donquixote\Cf\Text\TextInterface|null
   *   Summary in case the value is true.
   */
  public function getFalseSummary(): ?TextInterface;

}
