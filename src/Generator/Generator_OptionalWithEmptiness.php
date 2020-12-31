<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

use Donquixote\Cf\Emptiness\EmptinessInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;

class Generator_OptionalWithEmptiness implements GeneratorInterface {

  /**
   * @var \Donquixote\Cf\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  private $emptiness;

  /**
   * @param \Donquixote\Cf\Generator\GeneratorInterface $decorated
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\Emptiness\EmptinessInterface $emptiness
   */
  public function __construct(GeneratorInterface $decorated, CfSchema_OptionalInterface $schema, EmptinessInterface $emptiness) {
    $this->decorated = $decorated;
    $this->schema = $schema;
    $this->emptiness = $emptiness;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {

    if ($this->emptiness->confIsEmpty($conf)) {
      return $this->schema->getEmptyValue();
    }

    return $this->decorated->confGetValue($conf);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if ($this->emptiness->confIsEmpty($conf)) {
      return $this->schema->getEmptyPhp();
    }

    return $this->decorated->confGetPhp($conf);
  }
}
