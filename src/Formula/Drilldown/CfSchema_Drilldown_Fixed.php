<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\IdToSchema\IdToSchema_Fixed;
use Donquixote\OCUI\IdToSchema\IdToSchemaInterface;
use Donquixote\OCUI\Formula\Id\CfSchema_IdInterface;
use Donquixote\OCUI\Formula\Select\CfSchema_Select_Fixed;

class CfSchema_Drilldown_Fixed extends CfSchema_Drilldown_CustomKeysBase {

  /**
   * @var \Donquixote\OCUI\Formula\Select\CfSchema_Select_Fixed
   */
  private $idSchema;

  /**
   * @var \Donquixote\OCUI\IdToSchema\IdToSchema_Fixed
   */
  private $idToSchema;

  /**
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface[] $schemas
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
   * @param \Donquixote\OCUI\Formula\Select\CfSchema_Select_Fixed $idSchema
   * @param \Donquixote\OCUI\IdToSchema\IdToSchema_Fixed $idToSchema
   */
  private function __construct(CfSchema_Select_Fixed $idSchema, IdToSchema_Fixed $idToSchema) {
    $this->idSchema = $idSchema;
    $this->idToSchema = $idToSchema;
  }

  /**
   * @param string $id
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $schema
   * @param string $label
   * @param string $groupLabel
   *
   * @return static
   */
  public function withOption(string $id, CfSchemaInterface $schema, string $label, $groupLabel = '') {
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
