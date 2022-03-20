<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Exception\GeneratorException_IncompatibleConfiguration;
use Donquixote\Ock\Formula\Id\Formula_IdInterface;
use Donquixote\Ock\Formula\IdVal\Formula_IdValInterface;
use Donquixote\Ock\Util\ConfUtil;
use Donquixote\Ock\V2V\Id\V2V_Id_Trivial;
use Donquixote\Ock\V2V\Id\V2V_IdInterface;

class Generator_Id implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Formula\Id\Formula_IdInterface
   */
  private $formula;

  /**
   * @var \Donquixote\Ock\V2V\Id\V2V_IdInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Ock\Formula\Id\Formula_IdInterface $formula
   *
   * @return self
   */
  #[OckIncarnator]
  public static function createFromIdFormula(Formula_IdInterface $formula): Generator_Id {
    return new self($formula, new V2V_Id_Trivial());
  }

  /**
   * @param \Donquixote\Ock\Formula\IdVal\Formula_IdValInterface $formula
   *
   * @return self
   */
  public static function createFromIdValFormula(Formula_IdValInterface $formula): Generator_Id {
    return new self($formula->getDecorated(), $formula->getV2V());
  }

  /**
   * @param \Donquixote\Ock\Formula\Id\Formula_IdInterface $formula
   * @param \Donquixote\Ock\V2V\Id\V2V_IdInterface $v2v
   */
  public function __construct(Formula_IdInterface $formula, V2V_IdInterface $v2v) {
    $this->formula = $formula;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      throw new GeneratorException_IncompatibleConfiguration(
        'Required id empty for id formula.');
    }

    if (!$this->formula->idIsKnown($id)) {
      throw new GeneratorException_IncompatibleConfiguration(
        "Unknown id '$id' for id formula.");
    }

    return $this->v2v->idGetPhp($id);
  }

}
