<?php

namespace Donquixote\OCUI\DefinitionList;

interface DefinitionListInterface {

  /**
   * @return \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface[]
   */
  public function getDefinitions(): array;

}
