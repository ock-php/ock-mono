<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class IdToSchema_Buffer implements IdToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\IdToSchema\IdToSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  private $buffer = [];

  /**
   * @param \Donquixote\OCUI\IdToSchema\IdToSchemaInterface $decorated
   */
  public function __construct(IdToSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?FormulaInterface {
    // @todo Optimize with isset()? But allow NULL values?
    return array_key_exists($id, $this->buffer)
      ? $this->buffer[$id]
      : $this->buffer[$id] = $this->decorated->idGetSchema($id);
  }
}
