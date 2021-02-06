<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Formula\Id\CfSchema_IdInterface;

class IdToSchema_FilterDecorator implements IdToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\IdToSchema\IdToSchemaInterface
   */
  private $idToSchema;

  /**
   * @var \Donquixote\OCUI\Formula\Id\CfSchema_IdInterface
   */
  private $condition;

  /**
   * @param \Donquixote\OCUI\IdToSchema\IdToSchemaInterface $decorated
   * @param \Donquixote\OCUI\Formula\Id\CfSchema_IdInterface $condition
   *
   * @todo There should be a narrower interface for $condition parameter.
   */
  public function __construct(IdToSchemaInterface $decorated, CfSchema_IdInterface $condition) {
    $this->idToSchema = $decorated;
    $this->condition = $condition;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?CfSchemaInterface {

    if (!$this->condition->idIsKnown($id)) {
      return NULL;
    }

    return $this->idToSchema->idGetSchema($id);
  }
}
