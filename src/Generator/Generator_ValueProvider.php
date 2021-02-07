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
  private $schema;

  /**
   * @param \Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProviderInterface $schema
   */
  public function __construct(Formula_ValueProviderInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->schema->getPhp();
  }
}
