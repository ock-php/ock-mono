<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\ValueToValue;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Zoo\V2V\Value\V2V_ValueInterface;

class Formula_ValueToValue extends Formula_ValueToValueBase {

  /**
   * @var \Donquixote\ObCK\Zoo\V2V\Value\V2V_ValueInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\ObCK\Zoo\V2V\Value\V2V_ValueInterface $v2v
   */
  public function __construct(FormulaInterface $decorated, V2V_ValueInterface $v2v) {
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
