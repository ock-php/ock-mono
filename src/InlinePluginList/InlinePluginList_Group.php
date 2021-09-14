<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlinePluginList;

use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\V2V\Group\V2V_Group_Trivial;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;
use Donquixote\Ock\V2V\Value\V2V_Value_GroupV2V;

abstract class InlinePluginList_Group implements InlinePluginListInterface {

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\InlinePluginList\InlinePluginListInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromGroup(Formula_GroupInterface $formula, IncarnatorInterface $incarnator): ?InlinePluginListInterface {
    return self::create($formula, new V2V_Group_Trivial(), $incarnator);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\InlinePluginList\InlinePluginListInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromGroupVal(Formula_GroupValInterface $formula, IncarnatorInterface $incarnator): ?InlinePluginListInterface {
    return self::create($formula->getDecorated(), $formula->getV2V(), $incarnator);
  }

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $groupFormula
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\InlinePluginList\InlinePluginListInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(Formula_GroupInterface $groupFormula, V2V_GroupInterface $v2v, IncarnatorInterface $incarnator): ?InlinePluginListInterface {
    $itemFormulas = $groupFormula->getItemFormulas();
    if (count($itemFormulas) !== 1) {
      return NULL;
    }
    $itemFormula = reset($itemFormulas);
    $key = key($itemFormulas);
    $itemPluginList = InlinePluginList::fromFormula($itemFormula, $incarnator);
    if ($itemPluginList === NULL) {
      return NULL;
    }
    return new InlinePluginList_V2V(
      $itemPluginList,
      new V2V_Value_GroupV2V($v2v, $key));
  }

}
