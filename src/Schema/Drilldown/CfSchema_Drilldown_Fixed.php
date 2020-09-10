<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\IdToSchema\IdToSchema_Fixed;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;
use Donquixote\Cf\Schema\Select\CfSchema_Select_Fixed;

class CfSchema_Drilldown_Fixed extends CfSchema_Drilldown_CustomKeysBase {

  /**
   * @var \Donquixote\Cf\Schema\Select\CfSchema_Select_Fixed
   */
  private $idSchema;

  /**
   * @var \Donquixote\Cf\IdToSchema\IdToSchema_Fixed
   */
  private $idToSchema;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return self
   */
  public static function create(array $schemas = [], array $labels = []): CfSchema_Drilldown_Fixed {
    return new self(
      CfSchema_Select_Fixed::createFlat($labels),
      new IdToSchema_Fixed($schemas));
  }

  /**
   * @param \Donquixote\Cf\Schema\Select\CfSchema_Select_Fixed $idSchema
   * @param \Donquixote\Cf\IdToSchema\IdToSchema_Fixed $idToSchema
   */
  private function __construct(CfSchema_Select_Fixed $idSchema, IdToSchema_Fixed $idToSchema) {
    $this->idSchema = $idSchema;
    $this->idToSchema = $idToSchema;
  }

  /**
   * @param string $id
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param string $label
   * @param string $groupLabel
   *
   * @return static
   */
  public function withOption($id, CfSchemaInterface $schema, $label, $groupLabel = '') {
    $clone = clone $this;
    $clone->idSchema = $this->idSchema->withOption($id, $label, $groupLabel);
    $clone->idToSchema = $this->idToSchema->withSchema($id, $schema);
    return $clone;
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
