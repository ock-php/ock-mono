<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Schema\ValueProvider\CfSchema_ValueProviderInterface;

/**
 * @STA
 */
class Generator_ValueProvider implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Schema\ValueProvider\CfSchema_ValueProviderInterface
   */
  private $schema;

  /**
   * @param \Donquixote\OCUI\Schema\ValueProvider\CfSchema_ValueProviderInterface $schema
   */
  public function __construct(CfSchema_ValueProviderInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->schema->getPhp();
  }
}
