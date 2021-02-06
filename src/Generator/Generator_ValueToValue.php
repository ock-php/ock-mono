<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\ValueToValue\CfSchema_ValueToValueInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Zoo\V2V\Value\V2V_ValueInterface;

class Generator_ValueToValue extends Generator_DecoratorBase {

  /**
   * @var \Donquixote\OCUI\Formula\ValueToValue\CfSchema_ValueToValueInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\ValueToValue\CfSchema_ValueToValueInterface $valueToValueSchema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function create(CfSchema_ValueToValueInterface $valueToValueSchema, SchemaToAnythingInterface $schemaToAnything): ?self {

    $decorated = $schemaToAnything->schema(
      $valueToValueSchema->getDecorated(),
      GeneratorInterface::class);

    if (NULL === $decorated || !$decorated instanceof GeneratorInterface) {
      return NULL;
    }

    return new self($decorated, $valueToValueSchema->getV2V());
  }

  /**
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $decorated
   * @param \Donquixote\OCUI\Zoo\V2V\Value\V2V_ValueInterface $v2v
   */
  public function __construct(GeneratorInterface $decorated, V2V_ValueInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    $php = parent::confGetPhp($conf);
    return $this->v2v->phpGetPhp($php);
  }
}
