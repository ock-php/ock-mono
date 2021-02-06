<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

class IdToSchema_Class implements IdToSchemaInterface {

  /**
   * @var string
   */
  private $class;

  /**
   * @param string $class
   */
  public function __construct(string $class) {
    $this->class = $class;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?CfSchemaInterface {

    $candidate = new $this->class($id);

    if (!$candidate instanceof CfSchemaInterface) {
      return NULL;
    }

    return $candidate;
  }
}
