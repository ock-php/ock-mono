<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Drilldown;

use Donquixote\OCUI\IdToSchema\IdToSchemaInterface;
use Donquixote\OCUI\Schema\Id\CfSchema_IdInterface;

class CfSchema_Drilldown extends CfSchema_Drilldown_CustomKeysBase {

  /**
   * @var \Donquixote\OCUI\Schema\Id\CfSchema_IdInterface
   */
  private $idSchema;

  /**
   * @var \Donquixote\OCUI\IdToSchema\IdToSchemaInterface
   */
  private $idToSchema;

  /**
   * @param \Donquixote\OCUI\Schema\Id\CfSchema_IdInterface $idSchema
   * @param \Donquixote\OCUI\IdToSchema\IdToSchemaInterface $idToSchema
   *
   * @return self
   */
  public static function create(CfSchema_IdInterface $idSchema, IdToSchemaInterface $idToSchema): CfSchema_Drilldown {
    return new self($idSchema, $idToSchema);
  }

  /**
   * @param \Donquixote\OCUI\Schema\Id\CfSchema_IdInterface $idSchema
   * @param \Donquixote\OCUI\IdToSchema\IdToSchemaInterface $idToSchema
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
