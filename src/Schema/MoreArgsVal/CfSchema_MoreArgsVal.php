<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\MoreArgsVal;

use Donquixote\Cf\Schema\MoreArgs\CfSchema_MoreArgsInterface;
use Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface;

class CfSchema_MoreArgsVal extends CfSchema_MoreArgsValBase {

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Cf\Schema\MoreArgs\CfSchema_MoreArgsInterface $decorated
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $v2v
   */
  public function __construct(CfSchema_MoreArgsInterface $decorated, V2V_GroupInterface $v2v) {
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
