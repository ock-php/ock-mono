<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

abstract class Generator_DecoratorBase implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $decorated
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
