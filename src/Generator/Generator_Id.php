<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\Formula\IdVal\Formula_IdValInterface;
use Donquixote\OCUI\Util\ConfUtil;
use Donquixote\OCUI\Util\PhpUtil;
use Donquixote\OCUI\Zoo\V2V\Id\V2V_Id_Trivial;
use Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface;

class Generator_Id implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Id\Formula_IdInterface
   */
  private $formula;

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $formula
   *
   * @return self
   */
  public static function createFromIdFormula(Formula_IdInterface $formula): Generator_Id {
    return new self($formula, new V2V_Id_Trivial());
  }

  /**
   * @param \Donquixote\OCUI\Formula\IdVal\Formula_IdValInterface $formula
   *
   * @return self
   */
  public static function createFromIdValFormula(Formula_IdValInterface $formula): Generator_Id {
    return new self($formula->getDecorated(), $formula->getV2V());
  }

  /**
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $formula
   * @param \Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface $v2v
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
      return PhpUtil::incompatibleConfiguration('Required id empty for id formula.');
    }

    if (!$this->formula->idIsKnown($id)) {
      return PhpUtil::incompatibleConfiguration("Unknown id '$id' for id formula.");
    }

    return $this->v2v->idGetPhp($id);
  }
}
