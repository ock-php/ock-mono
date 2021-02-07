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
  private $schema;

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $schema
   *
   * @return self
   */
  public static function createFromIdFormula(Formula_IdInterface $schema): Generator_Id {
    return new self($schema, new V2V_Id_Trivial());
  }

  /**
   * @param \Donquixote\OCUI\Formula\IdVal\Formula_IdValInterface $schema
   *
   * @return self
   */
  public static function createFromIdValFormula(Formula_IdValInterface $schema): Generator_Id {
    return new self($schema->getDecorated(), $schema->getV2V());
  }

  /**
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $schema
   * @param \Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface $v2v
   */
  public function __construct(Formula_IdInterface $schema, V2V_IdInterface $v2v) {
    $this->schema = $schema;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return PhpUtil::incompatibleConfiguration('Required id empty for id schema.');
    }

    if (!$this->schema->idIsKnown($id)) {
      return PhpUtil::incompatibleConfiguration("Unknown id '$id' for id schema.");
    }

    return $this->v2v->idGetPhp($id);
  }
}
