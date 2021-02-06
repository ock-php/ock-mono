<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Boolean;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Text\TextInterface;

interface CfSchema_BooleanInterface extends CfSchemaInterface {

  /**
   * Gets a summary for true.
   *
   * @return \Donquixote\OCUI\Text\TextInterface|null
   *   Summary in case the value is true.
   */
  public function getTrueSummary(): ?TextInterface;

  /**
   * @return \Donquixote\OCUI\Text\TextInterface|null
   *   Summary in case the value is true.
   */
  public function getFalseSummary(): ?TextInterface;

}
