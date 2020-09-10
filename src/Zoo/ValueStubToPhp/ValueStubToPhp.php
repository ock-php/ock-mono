<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\ValueStubToPhp;

use Donquixote\Cf\Zoo\ValueStub\ValueStub_Group;
use Donquixote\Cf\Zoo\ValueStub\ValueStub_GroupVal;

class ValueStubToPhp {

  /**
   * @param \Donquixote\Cf\Zoo\ValueStub\ValueStub_Group $group
   * @param \Donquixote\Cf\Zoo\ValueStubToPhp\ValueStubToPhpInterface $valueStubToPhp
   *
   * @return string
   */
  public function group(ValueStub_Group $group, ValueStubToPhpInterface $valueStubToPhp): string {

    $snippets = [];
    foreach ($group->getItems() as $k => $item) {
      $snippets[$k] = $valueStubToPhp->php($item);
    }

    return '[' . implode(', ', $snippets) . ']';
  }

  /**
   * @param \Donquixote\Cf\Zoo\ValueStub\ValueStub_GroupVal $groupVal
   * @param \Donquixote\Cf\Zoo\ValueStubToPhp\ValueStubToPhpInterface $valueStubToPhp
   *
   * @return string
   */
  public function groupVal(ValueStub_GroupVal $groupVal, ValueStubToPhpInterface $valueStubToPhp): string {

    $snippets = [];
    foreach ($groupVal->getGroup()->getItems() as $k => $item) {
      $snippets[$k] = $valueStubToPhp->php($item);
    }

    return $groupVal->getV2v()->itemsPhpGetPhp($snippets);
  }

}
