<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests;

use Donquixote\ObCK\Formula\DecoKey\Formula_DecoKey;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface;

class FormulaTest extends FormulaTestBase {

  /**
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public function testFormula() {
    $formula = Formula::iface(IntOpInterface::class);
    $formula_to_anything = $this->getFormulaToAnything();
    $derived_formula = Formula::replace($formula, $formula_to_anything);
    self::assertInstanceOf(Formula_DecoKey::class, $derived_formula);
    self::assertNotSame($derived_formula, $formula);
  }

}
