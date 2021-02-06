<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Neutral;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

class CfSchema_Neutral_ProxyWithCallable extends CfSchema_Neutral_ProxyBase {

  /**
   * @var callable
   */
  private $schemaCallback;

  /**
   * @param callable $schemaCallback
   */
  public function __construct(callable $schemaCallback) {
    $this->schemaCallback = $schemaCallback;
  }

  /**
   * {@inheritdoc}
   */
  public function doGetDecorated(): CfSchemaInterface {

    $schema = \call_user_func($this->schemaCallback);

    if (!$schema instanceof CfSchemaInterface) {
      throw new \RuntimeException("Callback did not return a schema.");
    }

    return $schema;
  }
}
