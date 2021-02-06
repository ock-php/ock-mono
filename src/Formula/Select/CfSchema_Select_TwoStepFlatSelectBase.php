<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Formula\Select\Flat\CfSchema_FlatSelectInterface;

abstract class CfSchema_Select_TwoStepFlatSelectBase extends CfSchema_Select_TwoStepFlatSelectGrandBase {

  /**
   * @var \Donquixote\OCUI\Formula\Select\Flat\CfSchema_FlatSelectInterface
   */
  private $idSchema;

  /**
   * @param \Donquixote\OCUI\Formula\Select\Flat\CfSchema_FlatSelectInterface $idSchema
   */
  public function __construct(CfSchema_FlatSelectInterface $idSchema) {
    $this->idSchema = $idSchema;
  }

  /**
   * @return \Donquixote\OCUI\Formula\Select\Flat\CfSchema_FlatSelectInterface
   */
  protected function getIdSchema(): CfSchema_FlatSelectInterface {
    return $this->idSchema;
  }
}
