<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Schema\Boolean\CfSchema_BooleanInterface;
use Donquixote\OCUI\Schema\BoolVal\CfSchema_BoolValInterface;
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
   * @param \Donquixote\OCUI\Schema\Boolean\CfSchema_BooleanInterface $schema
   *
   * @return self
   */
  public static function createFromBooleanSchema(
    /** @noinspection PhpUnusedParameterInspection */ CfSchema_BooleanInterface $schema
  ): Generator_Boolean {
    return new self(new V2V_Boolean_Trivial());
  }

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Schema\BoolVal\CfSchema_BoolValInterface $schema
   *
   * @return self
   */
  public static function createFromBooleanValSchema(CfSchema_BoolValInterface $schema): Generator_Boolean {
    return new self($schema->getV2V());
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
