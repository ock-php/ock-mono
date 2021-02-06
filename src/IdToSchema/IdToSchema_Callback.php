<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class IdToSchema_Callback implements IdToSchemaInterface {

  /**
   * @var callable
   */
  private $callback;

  /**
   * @param callable $callback
   */
  public function __construct(callable $callback) {
    $this->callback = $callback;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?FormulaInterface {

    $candidate = \call_user_func($this->callback, $id);

    if (!$candidate instanceof FormulaInterface) {
      return NULL;
    }

    return $candidate;
  }
}
