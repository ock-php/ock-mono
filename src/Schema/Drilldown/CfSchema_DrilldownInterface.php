<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Drilldown;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\IdToSchema\IdToSchemaInterface;
use Donquixote\OCUI\Schema\Id\CfSchema_IdInterface;

interface CfSchema_DrilldownInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\OCUI\Schema\Id\CfSchema_IdInterface
   */
  public function getIdSchema(): CfSchema_IdInterface;

  /**
   * @return \Donquixote\OCUI\IdToSchema\IdToSchemaInterface
   */
  public function getIdToSchema(): IdToSchemaInterface;

  /**
   * @return string|null
   */
  public function getIdKey(): ?string;

  /**
   * @return string|null
   */
  public function getOptionsKey(): ?string;

}
