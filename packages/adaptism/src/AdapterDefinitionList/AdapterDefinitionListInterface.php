<?php

declare(strict_types=1);

namespace Ock\Adaptism\AdapterDefinitionList;

interface AdapterDefinitionListInterface {

  /**
   * @return \Ock\Adaptism\AdapterDefinition\AdapterDefinitionInterface[]
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  public function getDefinitions(): array;

}
