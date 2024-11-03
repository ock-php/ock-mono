<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Compiler;

use Ock\DependencyInjection\Parametric\PlaceholderInterface;
use Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass;

/**
 * Inserts placeholders for parametric arguments.
 */
class ResolveParametricArgumentsPass extends AbstractRecursivePass {

  /**
   * {@inheritdoc}
   */
  protected function processValue(mixed $value, bool $isRoot = FALSE) {
    if ($value instanceof PlaceholderInterface) {
      if (!$value->needsArguments()) {
        assert($this->container !== null);
        $value = $value->resolve([], $this->container);
      }
    }
    return parent::processValue($value, $isRoot);
  }

}
