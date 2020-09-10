<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Zoo\V2V\Value\V2V_ValueInterface;

class CfSchema_ValueToValue extends CfSchema_ValueToValueBase {

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Value\V2V_ValueInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\Cf\Zoo\V2V\Value\V2V_ValueInterface $v2v
   */
  public function __construct(CfSchemaInterface $decorated, V2V_ValueInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function getV2V(): V2V_ValueInterface {
    return $this->v2v;
  }
}
