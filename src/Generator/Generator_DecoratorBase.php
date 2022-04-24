<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

abstract class Generator_DecoratorBase implements GeneratorInterface {

  /**
   * @param \Donquixote\Ock\Generator\GeneratorInterface $decorated
   */
  protected function __construct(
    private GeneratorInterface $decorated,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->decorated->confGetPhp($conf);
  }

}
