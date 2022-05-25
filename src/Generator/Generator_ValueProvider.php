<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Ock\Formula\ValueProvider\Formula_ValueProviderInterface;

#[Adapter]
class Generator_ValueProvider implements GeneratorInterface {

  /**
   * @param \Donquixote\Ock\Formula\ValueProvider\Formula_ValueProviderInterface $formula
   */
  public function __construct(
    private readonly Formula_ValueProviderInterface $formula,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetPhp(mixed $conf): string {
    return $this->formula->getPhp();
  }

}
