<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests;

use Donquixote\Ock\Formula\DecoKey\Formula_DecoKey;
use Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\Ock\Formula\DecoShift\Formula_DecoShiftInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Iface\Formula_Iface;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FormulaTest extends FormulaTestBase {

  /**
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public function testFormula(): void {
    $formula = Formula::iface(IntOpInterface::class);
    self::assertInstanceOf(Formula_Iface::class, $formula);
    $adapter = $this->getAdapter();
    $derived_formula = $adapter->adapt($formula, Formula_DecoKeyInterface::class);
    # self::assertInstanceOf(Formula_Iface::class, $formula);
    self::assertInstanceOf(Formula_DecoKey::class, $derived_formula);
    self::assertNotSame($derived_formula, $formula);
  }

}
