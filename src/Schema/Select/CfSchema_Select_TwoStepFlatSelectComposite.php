<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Select;

use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface;

class CfSchema_Select_TwoStepFlatSelectComposite extends CfSchema_Select_TwoStepFlatSelectBase {

  /**
   * @var \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  private $idToSubSchema;

  /**
   * @param \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface $idSchema
   * @param \Donquixote\Cf\IdToSchema\IdToSchemaInterface $idToSubSchema
   */
  public function __construct(
    CfSchema_FlatSelectInterface $idSchema,
    IdToSchemaInterface $idToSubSchema
  ) {
    parent::__construct($idSchema);
    $this->idToSubSchema = $idToSubSchema;
  }

  /**
   * @param string $id
   *
   * @return CfSchema_FlatSelectInterface|null
   */
  protected function idGetSubSchema(string $id): ?CfSchema_FlatSelectInterface {

    $subSchema = $this->idToSubSchema->idGetSchema($id);

    if (!$subSchema instanceof CfSchema_FlatSelectInterface) {
      return NULL;
    }

    return $subSchema;
  }
}
