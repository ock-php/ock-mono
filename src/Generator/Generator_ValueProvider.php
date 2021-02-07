<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProviderInterface;

/**
 * @STA
 */
class Generator_ValueProvider implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProviderInterface
   */
  private $formula;

  /**
   * @param \Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProviderInterface $formula
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
