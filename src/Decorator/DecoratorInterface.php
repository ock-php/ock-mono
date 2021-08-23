<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Decorator;

/**
 * @see \Donquixote\OCUI\Generator\GeneratorInterface
 */
interface DecoratorInterface {

  /**
   * Decorates php code based on given configuration.
   *
   * @param mixed $conf
   *   Configuration for the decorator.
   * @param string $php
   *   PHP code to be decorated.
   *
   * @return string
   *   PHP code with decoration.
   */
  public function confDecoratePhp($conf, string $php): string;

}
