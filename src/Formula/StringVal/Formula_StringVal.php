<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\StringVal;

use Donquixote\ObCK\Formula\Textfield\Formula_TextfieldInterface;
use Donquixote\ObCK\V2V\String\V2V_StringInterface;

class Formula_StringVal extends Formula_StringValBase {

  /**
   * @var \Donquixote\ObCK\V2V\String\V2V_StringInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\ObCK\Formula\Textfield\Formula_TextfieldInterface $decorated
   * @param \Donquixote\ObCK\V2V\String\V2V_StringInterface $v2v
   */
  public function __construct(Formula_TextfieldInterface $decorated, V2V_StringInterface $v2v) {
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
