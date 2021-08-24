<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\Formula\IdVal\Formula_IdValInterface;
use Donquixote\ObCK\Util\ConfUtil;
use Donquixote\ObCK\Util\PhpUtil;
use Donquixote\ObCK\Zoo\V2V\Id\V2V_Id_Trivial;
use Donquixote\ObCK\Zoo\V2V\Id\V2V_IdInterface;

class Generator_Id implements GeneratorInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Id\Formula_IdInterface
   */
  private $formula;

  /**
   * @var \Donquixote\ObCK\Zoo\V2V\Id\V2V_IdInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Id\Formula_IdInterface $formula
   *
   * @return self
   */
  public static function createFromIdFormula(Formula_IdInterface $formula): Generator_Id {
    return new self($formula, new V2V_Id_Trivial());
  }

  /**
   * @param \Donquixote\ObCK\Formula\IdVal\Formula_IdValInterface $formula
   *
   * @return self
   */
  public static function createFromIdValFormula(Formula_IdValInterface $formula): Generator_Id {
    return new self($formula->getDecorated(), $formula->getV2V());
  }

  /**
   * @param \Donquixote\ObCK\Formula\Id\Formula_IdInterface $formula
   * @param \Donquixote\ObCK\Zoo\V2V\Id\V2V_IdInterface $v2v
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
