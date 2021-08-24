<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProviderInterface;

/**
 * @STA
 */
class Generator_ValueProvider implements GeneratorInterface {

  /**
   * @var \Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProviderInterface
   */
  private $formula;

  /**
   * @param \Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProviderInterface $formula
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
