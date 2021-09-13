<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\MoreArgsVal;

use Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgsInterface;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

class Formula_MoreArgsVal extends Formula_MoreArgsValBase {

  /**
   * @var \Donquixote\Ock\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgsInterface $decorated
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   */
  public function __construct(Formula_MoreArgsInterface $decorated, V2V_GroupInterface $v2v) {
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
