<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Neutral;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class CfSchema_Neutral_ProxyWithReference extends CfSchema_Neutral_ProxyBase {

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface|null
   */
  private $schemaRef;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface|null $schemaRef
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
