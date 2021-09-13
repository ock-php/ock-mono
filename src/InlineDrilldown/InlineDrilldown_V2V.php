<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlineDrilldown;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Id\Formula_IdInterface;
use Donquixote\Ock\Formula\ValueToValue\Formula_ValueToValue;
use Donquixote\Ock\V2V\Value\V2V_ValueInterface;

class InlineDrilldown_V2V implements InlineDrilldownInterface {

  /**
   * @var \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface
   */
  private InlineDrilldownInterface $decorated;

  /**
   * @var \Donquixote\Ock\V2V\Value\V2V_ValueInterface
   */
  private V2V_ValueInterface $v2v;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface $decorated
   * @param \Donquixote\Ock\V2V\Value\V2V_ValueInterface $v2v
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
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula(string $id): ?FormulaInterface {
    $formula = $this->decorated->idGetFormula($id);
    if ($formula === NULL) {
      return NULL;
    }
    return new Formula_ValueToValue($formula, $this->v2v);
  }

}
