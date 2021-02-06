<?php

namespace Donquixote\Cf\Contextualizer;

use Donquixote\ReflectionKit\Context\ContextInterface;

interface ContextualizerInterface {

  /**
   * @param \Donquixote\ReflectionKit\Context\ContextInterface|null $context
   *
   * @return string
   */
  public function contextGetSchema(?ContextInterface $context): string;

}
