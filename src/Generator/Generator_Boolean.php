<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\Formula\Boolean\Formula_BooleanInterface;
use Donquixote\ObCK\Formula\BoolVal\Formula_BoolValInterface;
use Donquixote\ObCK\V2V\Boolean\V2V_Boolean_Trivial;
use Donquixote\ObCK\V2V\Boolean\V2V_BooleanInterface;

class Generator_Boolean implements GeneratorInterface {

  /**
   * @var \Donquixote\ObCK\V2V\Boolean\V2V_BooleanInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Boolean\Formula_BooleanInterface $formula
   *
   * @return self
   */
  public static function createFromBooleanFormula(
    /** @noinspection PhpUnusedParameterInspection */ Formula_BooleanInterface $formula
  ): Generator_Boolean {
    return new self(new V2V_Boolean_Trivial());
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\BoolVal\Formula_BoolValInterface $formula
   *
   * @return self
   */
  public static function createFromBooleanValFormula(Formula_BoolValInterface $formula): Generator_Boolean {
    return new self($formula->getV2V());
  }

  /**
   * @param \Donquixote\ObCK\V2V\Boolean\V2V_BooleanInterface $v2v
   */
  public function __construct(V2V_BooleanInterface $v2v) {
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    $boolean = !empty($conf);

    return $boolean
      ? $this->v2v->getTruePhp()
      : $this->v2v->getFalsePhp();
  }

}
