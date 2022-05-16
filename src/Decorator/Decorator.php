<?php

declare(strict_types=1);

namespace Donquixote\Ock\Decorator;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\FormulaAdapter;
use Donquixote\Ock\Util\UtilBase;

final class Decorator extends UtilBase {

  /**
   * Materializes a Decorator from an interface.
   *
   * @param string $interface
   *   Interface name.
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\Ock\Decorator\DecoratorInterface|null
   *   Decorator. Evaluating the code of this Decorator should create an
   *   instance of $interface.
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   *   Cannot build a Decorator for the given interface.
   */
  public static function fromIface(
    string $interface,
    UniversalAdapterInterface $universalAdapter
  ): ?DecoratorInterface {
    return self::fromFormula(
      Formula::iface($interface),
      $universalAdapter);
  }

  /**
   * Materializes a Decorator from a formula.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\Ock\Decorator\DecoratorInterface
   *   Materialized Decorator.
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   *   Cannot build a Decorator for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    UniversalAdapterInterface $universalAdapter
  ): DecoratorInterface {
    return FormulaAdapter::requireObject(
      $formula,
      DecoratorInterface::class,
      $universalAdapter);
  }

}
