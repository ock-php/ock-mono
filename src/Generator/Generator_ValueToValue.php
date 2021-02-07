<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\ValueToValue\Formula_ValueToValueInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Zoo\V2V\Value\V2V_ValueInterface;

class Generator_ValueToValue extends Generator_DecoratorBase {

  /**
   * @var \Donquixote\OCUI\Formula\ValueToValue\Formula_ValueToValueInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\ValueToValue\Formula_ValueToValueInterface $valueToValueFormula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function create(Formula_ValueToValueInterface $valueToValueFormula, FormulaToAnythingInterface $schemaToAnything): ?self {

    $decorated = $schemaToAnything->schema(
      $valueToValueFormula->getDecorated(),
      GeneratorInterface::class);

    if (NULL === $decorated || !$decorated instanceof GeneratorInterface) {
      return NULL;
    }

    return new self($decorated, $valueToValueFormula->getV2V());
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
