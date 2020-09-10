<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaBase\Decorator;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class CfSchema_DecoratorBase implements CfSchemaInterface {

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $decorated
   */
  public function __construct(CfSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   *   The non-optional version.
   */
  public function getDecorated(): CfSchemaInterface {
    return $this->decorated;
  }
}
