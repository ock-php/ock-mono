<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

abstract class Generator_DecoratorBase implements GeneratorInterface {

  /**
   * @var \Donquixote\ObCK\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\ObCK\Generator\GeneratorInterface $decorated
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
