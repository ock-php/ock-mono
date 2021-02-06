<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Neutral;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

class CfSchema_Neutral_ProxyWithReference extends CfSchema_Neutral_ProxyBase {

  /**
   * @var \Donquixote\OCUI\Core\Formula\CfSchemaInterface|null
   */
  private $schemaRef;

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface|null $schemaRef
   */
  public function __construct(CfSchemaInterface &$schemaRef = NULL) {
    $this->schemaRef =& $schemaRef;
  }

  /**
   * {@inheritdoc}
   */
  public function doGetDecorated(): CfSchemaInterface {

    if (!$this->schemaRef instanceof CfSchemaInterface) {
      throw new \RuntimeException("Schema reference is still empty.");
    }

    return $this->schemaRef;
  }
}
