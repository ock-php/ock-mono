<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests;

use Donquixote\Ock\Formula\DecoKey\Formula_DecoKey;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;

class FormulaTest extends FormulaTestBase {

  /**
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public function testFormula() {
    $formula = Formula::iface(IntOpInterface::class);
    $formula_to_anything = $this->getFormulaToAnything();
    $derived_formula = Formula::replace($formula, $formula_to_anything);
    self::assertInstanceOf(Formula_DecoKey::class, $derived_formula);
    self::assertNotSame($derived_formula, $formula);
  }

}
