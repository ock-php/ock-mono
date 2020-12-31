<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

abstract class Generator_DecoratorBase implements GeneratorInterface {

  /**
   * @var \Donquixote\Cf\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Generator\GeneratorInterface $decorated
   */
  protected function __construct(GeneratorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {
    return $this->decorated->confGetValue($conf);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->decorated->confGetPhp($conf);
  }
}
