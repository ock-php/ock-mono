<?php

declare(strict_types=1);

namespace Donquixote\Ock\Decorator;

/**
 * @see \Donquixote\Ock\Generator\GeneratorInterface
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
   *
   * @throws \Donquixote\Ock\Exception\GeneratorException
   *   Incompatible or unsupported configuration.
   */
  public function confDecoratePhp($conf, string $php): string;

}
