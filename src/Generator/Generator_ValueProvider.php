<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Formula\ValueProvider\Formula_ValueProviderInterface;

#[OckIncarnator]
class Generator_ValueProvider implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Formula\ValueProvider\Formula_ValueProviderInterface
   */
  private $formula;

  /**
   * @param \Donquixote\Ock\Formula\ValueProvider\Formula_ValueProviderInterface $formula
   */
  public function __construct(Formula_ValueProviderInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->formula->getPhp();
  }

}
