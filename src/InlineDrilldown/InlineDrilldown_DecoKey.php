<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlineDrilldown;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

abstract class InlineDrilldown_DecoKey implements InlineDrilldownInterface {

  /**
   * @param \Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function fromFormula(Formula_DecoKeyInterface $formula, UniversalAdapterInterface $universalAdapter): InlineDrilldownInterface {
    return InlineDrilldown::fromFormula(
      $formula->getDecorated(),
      $universalAdapter);
  }

}
