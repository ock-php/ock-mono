<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Boolean;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface CfSchema_BooleanInterface extends CfSchemaInterface {

  /**
   * @return string|null
   */
  public function getTrueSummary(): ?string;

  /**
   * @return string|null
   */
  public function getFalseSummary(): ?string;

}
