<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\IdToSchema\IdToSchemaInterface;
use Donquixote\OCUI\Formula\Id\Formula_IdInterface;

class Formula_Drilldown extends Formula_Drilldown_CustomKeysBase {

  /**
   * @var \Donquixote\OCUI\Formula\Id\Formula_IdInterface
   */
  private $idSchema;

  /**
   * @var \Donquixote\OCUI\IdToSchema\IdToSchemaInterface
   */
  private $idToSchema;

  /**
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $idSchema
   * @param \Donquixote\OCUI\IdToSchema\IdToSchemaInterface $idToSchema
   *
   * @return self
   */
  public static function create(Formula_IdInterface $idSchema, IdToSchemaInterface $idToSchema): Formula_Drilldown {
    return new self($idSchema, $idToSchema);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $idSchema
   * @param \Donquixote\OCUI\IdToSchema\IdToSchemaInterface $idToSchema
   */
  public function __construct(Formula_IdInterface $idSchema, IdToSchemaInterface $idToSchema) {
    $this->idSchema = $idSchema;
    $this->idToSchema = $idToSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function getIdSchema(): Formula_IdInterface {
    return $this->idSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function getIdToSchema(): IdToSchemaInterface {
    return $this->idToSchema;
  }
}
