<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValueInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\V2V\Value\V2V_ValueInterface;

class Generator_ValueToValue extends Generator_DecoratorBase {

  /**
   * @var \Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValueInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValueInterface $valueToValueFormula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self|null
   */
  public static function create(Formula_ValueToValueInterface $valueToValueFormula, NurseryInterface $formulaToAnything): ?self {

    $decorated = $formulaToAnything->breed(
      $valueToValueFormula->getDecorated(),
      GeneratorInterface::class);

    if (NULL === $decorated || !$decorated instanceof GeneratorInterface) {
      return NULL;
    }

    return new self($decorated, $valueToValueFormula->getV2V());
  }

  /**
   * @param \Donquixote\ObCK\Generator\GeneratorInterface $decorated
   * @param \Donquixote\ObCK\V2V\Value\V2V_ValueInterface $v2v
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
