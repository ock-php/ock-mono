<?php

namespace Donquixote\ObCK\DefinitionList;

interface DefinitionListInterface {

  /**
   * @return \Donquixote\ObCK\Formula\Drilldown\Option\DrilldownOptionInterface[]
   */
  public function getDefinitions(): array;

}
