<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\StringVal;

use Donquixote\OCUI\Schema\Textfield\CfSchema_TextfieldInterface;
use Donquixote\OCUI\Zoo\V2V\String\V2V_StringInterface;

class CfSchema_StringVal extends CfSchema_StringValBase {

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\String\V2V_StringInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\OCUI\Schema\Textfield\CfSchema_TextfieldInterface $decorated
   * @param \Donquixote\OCUI\Zoo\V2V\String\V2V_StringInterface $v2v
   */
  public function __construct(CfSchema_TextfieldInterface $decorated, V2V_StringInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function getV2V(): V2V_StringInterface {
    return $this->v2v;
  }
}
