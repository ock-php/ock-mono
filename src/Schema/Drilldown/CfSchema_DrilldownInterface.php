<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;

interface CfSchema_DrilldownInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\Cf\Schema\Id\CfSchema_IdInterface
   */
  public function getIdSchema(): CfSchema_IdInterface;

  /**
   * @return \Donquixote\Cf\IdToSchema\IdToSchemaInterface
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
