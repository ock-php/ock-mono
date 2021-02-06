<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaBase\Decorator;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

class CfSchema_DecoratorBase implements CfSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $decorated
   */
  public function __construct(CfSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   *   The non-optional version.
   */
  public function getDecorated(): CfSchemaInterface {
    return $this->decorated;
  }
}
