<?php
declare(strict_types=1);

namespace Donquixote\Cf\IdToSchema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;

class IdToSchema_FilterDecorator implements IdToSchemaInterface {

  /**
   * @var \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  private $idToSchema;

  /**
   * @var \Donquixote\Cf\Schema\Id\CfSchema_IdInterface
   */
  private $condition;

  /**
   * @param \Donquixote\Cf\IdToSchema\IdToSchemaInterface $decorated
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $condition
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
