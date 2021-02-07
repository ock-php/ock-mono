<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\Boolean\Formula_BooleanInterface;
use Donquixote\OCUI\Formula\BoolVal\Formula_BoolValInterface;
use Donquixote\OCUI\Zoo\V2V\Boolean\V2V_Boolean_Trivial;
use Donquixote\OCUI\Zoo\V2V\Boolean\V2V_BooleanInterface;

class Generator_Boolean implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Boolean\V2V_BooleanInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Boolean\Formula_BooleanInterface $formula
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
   * @param \Donquixote\OCUI\Formula\BoolVal\Formula_BoolValInterface $formula
   *
   * @return self
   */
  public static function createFromBooleanValFormula(Formula_BoolValInterface $formula): Generator_Boolean {
    return new self($formula->getV2V());
  }

  /**
   * @param \Donquixote\OCUI\Zoo\V2V\Boolean\V2V_BooleanInterface $v2v
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
