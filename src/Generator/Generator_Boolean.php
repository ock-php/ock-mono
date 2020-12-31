<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

use Donquixote\Cf\Schema\Boolean\CfSchema_BooleanInterface;
use Donquixote\Cf\Schema\BoolVal\CfSchema_BoolValInterface;
use Donquixote\Cf\Zoo\V2V\Boolean\V2V_Boolean_Trivial;
use Donquixote\Cf\Zoo\V2V\Boolean\V2V_BooleanInterface;

class Generator_Boolean implements GeneratorInterface {

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Boolean\V2V_BooleanInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Boolean\CfSchema_BooleanInterface $schema
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
   * @param \Donquixote\Cf\Schema\BoolVal\CfSchema_BoolValInterface $schema
   *
   * @return self
   */
  public static function createFromBooleanValSchema(CfSchema_BoolValInterface $schema): Generator_Boolean {
    return new self($schema->getV2V());
  }

  /**
   * @param \Donquixote\Cf\Zoo\V2V\Boolean\V2V_BooleanInterface $v2v
   */
  public function __construct(V2V_BooleanInterface $v2v) {
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {

    $boolean = !empty($conf);

    return $boolean
      ? $this->v2v->getTrueValue()
      : $this->v2v->getFalseValue();
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
