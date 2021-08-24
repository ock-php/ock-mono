<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\GroupVal;

use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface;

class Formula_GroupVal_Decorator extends Formula_GroupValBase {

  /**
   * @var \Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $decorated
   * @param \Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface $v2v
   */
  public function __construct(Formula_GroupInterface $decorated, V2V_GroupInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function getV2V(): V2V_GroupInterface {
    return $this->v2v;
  }
}
