<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Select;

use Donquixote\OCUI\IdToSchema\IdToSchemaInterface;
use Donquixote\OCUI\Schema\Select\Flat\CfSchema_FlatSelectInterface;

class CfSchema_Select_TwoStepFlatSelectComposite extends CfSchema_Select_TwoStepFlatSelectBase {

  /**
   * @var \Donquixote\OCUI\IdToSchema\IdToSchemaInterface
   */
  private $idToSubSchema;

  /**
   * @param \Donquixote\OCUI\Schema\Select\Flat\CfSchema_FlatSelectInterface $idSchema
   * @param \Donquixote\OCUI\IdToSchema\IdToSchemaInterface $idToSubSchema
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
