<?php

declare(strict_types=1);

namespace Donquixote\Ock\Optionlessness;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\UtilBase;

/**
 * Incarnator from Formula_Group* to Optionlessness*.
 */
final class Optionlessness_Group extends UtilBase {

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $group
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Optionlessness\OptionlessnessInterface
   */
  #[OckIncarnator]
  public static function fromFormula(Formula_GroupInterface $group, IncarnatorInterface $incarnator): OptionlessnessInterface {
    foreach ($group->getItemFormulas() as $item) {
      if (!Optionlessness::checkFormula($item, $incarnator)) {
        // At least one group item has config options.
        return new Optionlessness(FALSE);
      }
    }
    // None of the group items are configurable.
    return new Optionlessness(TRUE);
  }

}
