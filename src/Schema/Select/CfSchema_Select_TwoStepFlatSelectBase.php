<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Select;

use Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface;

abstract class CfSchema_Select_TwoStepFlatSelectBase extends CfSchema_Select_TwoStepFlatSelectGrandBase {

  /**
   * @var \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface
   */
  private $idSchema;

  /**
   * @param \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface $idSchema
   */
  public function __construct(CfSchema_FlatSelectInterface $idSchema) {
    $this->idSchema = $idSchema;
  }

  /**
   * @return \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface
   */
  protected function getIdSchema(): CfSchema_FlatSelectInterface {
    return $this->idSchema;
  }
}
