<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

abstract class Generator_DecoratorBase implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Ock\Generator\GeneratorInterface $decorated
   */
  protected function __construct(GeneratorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->decorated->confGetPhp($conf);
  }

}
