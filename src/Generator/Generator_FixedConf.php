<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

use Donquixote\Cf\Schema\FixedConf\CfSchema_FixedConfInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class Generator_FixedConf implements GeneratorInterface {

  /**
   * @var \Donquixote\Cf\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\FixedConf\CfSchema_FixedConfInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Generator\GeneratorInterface
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_FixedConfInterface $schema, SchemaToAnythingInterface $schemaToAnything): GeneratorInterface {
    return new self(
      Generator::fromSchema($schema->getDecorated(), $schemaToAnything),
      $schema->getConf());
  }

  /**
   * @param \Donquixote\Cf\Generator\GeneratorInterface $decorated
   * @param mixed $conf
   */
  public function __construct(GeneratorInterface $decorated, $conf) {
    $this->decorated = $decorated;
    $this->conf = $conf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->decorated->confGetPhp($this->conf);
  }
}
