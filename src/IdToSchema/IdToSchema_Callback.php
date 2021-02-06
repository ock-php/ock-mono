<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

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
  public function idGetSchema($id): ?CfSchemaInterface {

    $candidate = \call_user_func($this->callback, $id);

    if (!$candidate instanceof CfSchemaInterface) {
      return NULL;
    }

    return $candidate;
  }
}
