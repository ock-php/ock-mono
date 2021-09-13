<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\GroupVal;

use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

class Formula_GroupVal_Decorator extends Formula_GroupValBase {

  /**
   * @var \Donquixote\Ock\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $decorated
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
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
