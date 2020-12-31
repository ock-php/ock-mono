<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

use Donquixote\Cf\Exception\EvaluatorException;
use Donquixote\Cf\Schema\Para\CfSchema_ParaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\PhpUtil;

class Generator_Para implements GeneratorInterface {

  /**
   * @var \Donquixote\Cf\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Generator\GeneratorInterface
   */
  private $paraGenerator;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Para\CfSchema_ParaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_ParaInterface $schema, SchemaToAnythingInterface $schemaToAnything): Generator_Para {
    return new self(
      Generator::fromSchema($schema->getDecorated(), $schemaToAnything),
      Generator::fromSchema($schema->getParaSchema(), $schemaToAnything));
  }

  /**
   * @param \Donquixote\Cf\Generator\GeneratorInterface $decorated
   * @param \Donquixote\Cf\Generator\GeneratorInterface $paraGenerator
   */
  public function __construct(GeneratorInterface $decorated, GeneratorInterface $paraGenerator) {
    $this->decorated = $decorated;
    $this->paraGenerator = $paraGenerator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {
    $paraConf = $this->decorated->confGetValue($conf);
    return $this->paraGenerator->confGetValue($paraConf);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    try {
      $paraConf = $this->decorated->confGetValue($conf);
    }
    catch (EvaluatorException $e) {
      return PhpUtil::incompatibleConfiguration($e->getMessage());
    }

    return $this->paraGenerator->confGetPhp($paraConf);
  }
}
