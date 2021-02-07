<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\IdToSchema\IdToSchemaInterface;
use Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface;

class Formula_Select_TwoStepFlatSelectComposite extends Formula_Select_TwoStepFlatSelectBase {

  /**
   * @var \Donquixote\OCUI\IdToSchema\IdToSchemaInterface
   */
  private $idToSubSchema;

  /**
   * @param \Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface $idSchema
   * @param \Donquixote\OCUI\IdToSchema\IdToSchemaInterface $idToSubSchema
   */
  public function __construct(
    Formula_FlatSelectInterface $idSchema,
    IdToSchemaInterface $idToSubSchema
  ) {
    parent::__construct($idSchema);
    $this->idToSubSchema = $idToSubSchema;
  }

  /**
   * @param string $id
   *
   * @return Formula_FlatSelectInterface|null
   */
  protected function idGetSubSchema(string $id): ?Formula_FlatSelectInterface {

    $subSchema = $this->idToSubSchema->idGetSchema($id);

    if (!$subSchema instanceof Formula_FlatSelectInterface) {
      return NULL;
    }

    return $subSchema;
  }
}
