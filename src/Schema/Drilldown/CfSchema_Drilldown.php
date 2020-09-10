<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;

class CfSchema_Drilldown extends CfSchema_Drilldown_CustomKeysBase {

  /**
   * @var \Donquixote\Cf\Schema\Id\CfSchema_IdInterface
   */
  private $idSchema;

  /**
   * @var \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  private $idToSchema;

  /**
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $idSchema
   * @param \Donquixote\Cf\IdToSchema\IdToSchemaInterface $idToSchema
   *
   * @return self
   */
  public static function create(CfSchema_IdInterface $idSchema, IdToSchemaInterface $idToSchema): CfSchema_Drilldown {
    return new self($idSchema, $idToSchema);
  }

  /**
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $idSchema
   * @param \Donquixote\Cf\IdToSchema\IdToSchemaInterface $idToSchema
   */
  public function __construct(CfSchema_IdInterface $idSchema, IdToSchemaInterface $idToSchema) {
    $this->idSchema = $idSchema;
    $this->idToSchema = $idToSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function getIdSchema(): CfSchema_IdInterface {
    return $this->idSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function getIdToSchema(): IdToSchemaInterface {
    return $this->idToSchema;
  }
}
