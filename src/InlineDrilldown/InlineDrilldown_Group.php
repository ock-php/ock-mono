<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlineDrilldown;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\V2V\Group\V2V_Group_Trivial;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;
use Donquixote\Ock\V2V\Value\V2V_Value_GroupV2V;

abstract class InlineDrilldown_Group implements InlineDrilldownInterface {

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function fromGroup(
    Formula_GroupInterface $formula,
    UniversalAdapterInterface $universalAdapter,
  ): ?InlineDrilldownInterface {
    return self::create(
      $formula,
      new V2V_Group_Trivial(),
      $universalAdapter,
    );
  }

  /**
   * @param \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function fromGroupVal(
    Formula_GroupValInterface $formula,
    UniversalAdapterInterface $universalAdapter,
  ): ?InlineDrilldownInterface {
    return self::create(
      $formula->getDecorated(),
      $formula->getV2V(),
      $universalAdapter,
    );
  }

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $groupFormula
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public static function create(Formula_GroupInterface $groupFormula, V2V_GroupInterface $v2v, UniversalAdapterInterface $universalAdapter): ?InlineDrilldownInterface {
    $itemFormulas = $groupFormula->getItemFormulas();
    if (count($itemFormulas) !== 1) {
      return NULL;
    }
    return new InlineDrilldown_V2V(
      InlineDrilldown::fromFormula(
        reset($itemFormulas),
        $universalAdapter,
      ),
      new V2V_Value_GroupV2V($v2v, key($itemFormulas)),
    );
  }

}
