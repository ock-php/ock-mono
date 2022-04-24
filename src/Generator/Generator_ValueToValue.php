<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\ValueToValue\Formula_ValueToValueInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\V2V\Value\V2V_ValueInterface;

class Generator_ValueToValue extends Generator_DecoratorBase {

  /**
   * @var \Donquixote\Ock\Formula\ValueToValue\Formula_ValueToValueInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Ock\Formula\ValueToValue\Formula_ValueToValueInterface $valueToValueFormula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   */
  #[Adapter]
  public static function create(Formula_ValueToValueInterface $valueToValueFormula, UniversalAdapterInterface $universalAdapter): ?self {

    try {
      $decorated = Generator::fromFormula(
        $valueToValueFormula->getDecorated(),
        $universalAdapter);
    }
    catch (IncarnatorException $e) {
      return NULL;
    }

    return new self($decorated, $valueToValueFormula->getV2V());
  }

  /**
   * @param \Donquixote\Ock\Generator\GeneratorInterface $decorated
   * @param \Donquixote\Ock\V2V\Value\V2V_ValueInterface $v2v
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
