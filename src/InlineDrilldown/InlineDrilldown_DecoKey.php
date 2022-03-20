<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlineDrilldown;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

abstract class InlineDrilldown_DecoKey implements InlineDrilldownInterface {

  /**
   * @param \Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  #[OckIncarnator]
  public static function fromFormula(Formula_DecoKeyInterface $formula, IncarnatorInterface $incarnator): InlineDrilldownInterface {
    return InlineDrilldown::fromFormula(
      $formula->getDecorated(),
      $incarnator);
  }

}
