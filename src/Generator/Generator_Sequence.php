<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\Schema\SequenceVal\CfSchema_SequenceValInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\Zoo\V2V\Sequence\V2V_Sequence_Trivial;
use Donquixote\Cf\Zoo\V2V\Sequence\V2V_SequenceInterface;

class Generator_Sequence implements GeneratorInterface {

  /**
   * @var \Donquixote\Cf\Generator\GeneratorInterface
   */
  private $itemGenerator;

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Sequence\V2V_SequenceInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromSequenceSchema(CfSchema_SequenceInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Generator_Sequence {
    return self::create($schema, new V2V_Sequence_Trivial(), $schemaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\SequenceVal\CfSchema_SequenceValInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromSequenceValSchema(CfSchema_SequenceValInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Generator_Sequence {
    return self::create($schema->getDecorated(), $schema->getV2V(), $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\Zoo\V2V\Sequence\V2V_SequenceInterface $v2v
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  private static function create(CfSchema_SequenceInterface $schema, V2V_SequenceInterface $v2v, SchemaToAnythingInterface $schemaToAnything): ?Generator_Sequence {

    $itemGenerator = Generator::fromSchema(
      $schema->getItemSchema(),
      $schemaToAnything
    );

    if (NULL === $itemGenerator) {
      return NULL;
    }

    return new self($itemGenerator, $v2v);
  }

  /**
   * @param \Donquixote\Cf\Generator\GeneratorInterface $itemGenerator
   * @param \Donquixote\Cf\Zoo\V2V\Sequence\V2V_SequenceInterface $v2v
   */
  protected function __construct(GeneratorInterface $itemGenerator, V2V_SequenceInterface $v2v) {
    $this->itemGenerator = $itemGenerator;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!\is_array($conf)) {
      return PhpUtil::incompatibleConfiguration("Configuration must be an array or NULL.");
    }

    $phpStatements = [];
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return PhpUtil::incompatibleConfiguration("Sequence array keys must be non-negative integers.");
      }

      $phpStatements[] = $this->itemGenerator->confGetPhp($itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }
}
