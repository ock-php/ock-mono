<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Formula;
use Donquixote\OCUI\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface;

class FormulaTest extends FormulaTestBase {

  public function testFormula() {
    $formula = Formula::iface(IntOpInterface::class);
    $formula_to_anything = $this->getFormulaToAnything();
    $derived_formula = Formula::replace($formula, $formula_to_anything);
    self::assertInstanceOf(Formula_PluginListInterface::class, $derived_formula);
    self::assertNotSame($derived_formula, $formula);
  }

}
