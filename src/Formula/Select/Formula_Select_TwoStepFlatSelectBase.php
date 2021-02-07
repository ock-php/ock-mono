<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface;

abstract class Formula_Select_TwoStepFlatSelectBase extends Formula_Select_TwoStepFlatSelectGrandBase {

  /**
   * @var \Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface
   */
  private $idSchema;

  /**
   * @param \Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface $idSchema
   */
  public function __construct(Formula_FlatSelectInterface $idSchema) {
    $this->idSchema = $idSchema;
  }

  /**
   * @return \Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface
   */
  protected function getIdSchema(): Formula_FlatSelectInterface {
    return $this->idSchema;
  }
}
