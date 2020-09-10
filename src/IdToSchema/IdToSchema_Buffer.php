<?php
declare(strict_types=1);

namespace Donquixote\Cf\IdToSchema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class IdToSchema_Buffer implements IdToSchemaInterface {

  /**
   * @var \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface[]
   */
  private $buffer = [];

  /**
   * @param \Donquixote\Cf\IdToSchema\IdToSchemaInterface $decorated
   */
  public function __construct(IdToSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?CfSchemaInterface {
    // @todo Optimize with isset()? But allow NULL values?
    return array_key_exists($id, $this->buffer)
      ? $this->buffer[$id]
      : $this->buffer[$id] = $this->decorated->idGetSchema($id);
  }
}
