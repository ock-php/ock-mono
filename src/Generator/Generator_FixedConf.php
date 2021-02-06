<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Schema\FixedConf\CfSchema_FixedConfInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;

class Generator_FixedConf implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Schema\FixedConf\CfSchema_FixedConfInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_FixedConfInterface $schema, SchemaToAnythingInterface $schemaToAnything): GeneratorInterface {
    return new self(
      Generator::fromSchema($schema->getDecorated(), $schemaToAnything),
      $schema->getConf());
  }

  /**
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $decorated
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
