<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\Para\Formula_ParaInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Util\PhpUtil;

class Generator_Para implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface
   */
  private $paraGenerator;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Para\Formula_ParaInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function create(Formula_ParaInterface $schema, SchemaToAnythingInterface $schemaToAnything): Generator_Para {
    return new self(
      Generator::fromSchema($schema->getDecorated(), $schemaToAnything),
      Generator::fromSchema($schema->getParaSchema(), $schemaToAnything));
  }

  /**
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $decorated
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $paraGenerator
   */
  public function __construct(GeneratorInterface $decorated, GeneratorInterface $paraGenerator) {
    $this->decorated = $decorated;
    $this->paraGenerator = $paraGenerator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    $paraConfPhp = $this->decorated->confGetPhp($conf);

    try {
      // @todo Use a service that can pass in variables!
      $paraConf = eval($paraConfPhp);
    }
    catch (\Exception $e) {
      return PhpUtil::incompatibleConfiguration($e->getMessage());
    }

    return $this->paraGenerator->confGetPhp($paraConf);
  }
}
