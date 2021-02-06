<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaBase\Decorator;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

class CfSchema_DecoratorBase implements CfSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $decorated
   */
  public function __construct(CfSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   *   The non-optional version.
   */
  public function getDecorated(): CfSchemaInterface {
    return $this->decorated;
  }
}
