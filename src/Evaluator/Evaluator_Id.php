<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;
use Donquixote\Cf\Schema\IdVal\CfSchema_IdValInterface;
use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\Zoo\V2V\Id\V2V_Id_Trivial;
use Donquixote\Cf\Zoo\V2V\Id\V2V_IdInterface;

class Evaluator_Id implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Id\CfSchema_IdInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Id\V2V_IdInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $schema
   *
   * @return self
   */
  public static function createFromIdSchema(CfSchema_IdInterface $schema): Evaluator_Id {
    return new self($schema, new V2V_Id_Trivial());
  }

  /**
   * @param \Donquixote\Cf\Schema\IdVal\CfSchema_IdValInterface $schema
   *
   * @return self
   */
  public static function createFromIdValSchema(CfSchema_IdValInterface $schema): Evaluator_Id {
    return new self($schema->getDecorated(), $schema->getV2V());
  }

  /**
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $schema
   * @param \Donquixote\Cf\Zoo\V2V\Id\V2V_IdInterface $v2v
   */
  public function __construct(CfSchema_IdInterface $schema, V2V_IdInterface $v2v) {
    $this->schema = $schema;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      throw new EvaluatorException_IncompatibleConfiguration('Required id empty for id schema.');
    }

    if (!$this->schema->idIsKnown($id)) {
      $schemaClass = \get_class($this->schema);
      throw new EvaluatorException_IncompatibleConfiguration("Unknown id '$id' for schema of class $schemaClass.");
    }

    return $this->v2v->idGetValue($id);
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
