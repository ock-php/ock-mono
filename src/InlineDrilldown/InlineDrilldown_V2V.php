<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlineDrilldown;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue;
use Donquixote\ObCK\V2V\Value\V2V_ValueInterface;

class InlineDrilldown_V2V implements InlineDrilldownInterface {

  /**
   * @var \Donquixote\ObCK\InlineDrilldown\InlineDrilldownInterface
   */
  private InlineDrilldownInterface $decorated;

  /**
   * @var \Donquixote\ObCK\V2V\Value\V2V_ValueInterface
   */
  private V2V_ValueInterface $v2v;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\InlineDrilldown\InlineDrilldownInterface $decorated
   * @param \Donquixote\ObCK\V2V\Value\V2V_ValueInterface $v2v
   */
  public function __construct(InlineDrilldownInterface $decorated, V2V_ValueInterface $v2v) {
    $this->decorated = $decorated;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function getIdFormula(): Formula_IdInterface {
    return $this->decorated->getIdFormula();
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula(string $id): ?FormulaInterface {
    $formula = $this->decorated->idGetFormula($id);
    if ($formula === NULL) {
      return NULL;
    }
    return new Formula_ValueToValue($formula, $this->v2v);
  }

}
