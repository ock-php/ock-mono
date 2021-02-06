<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Neutral;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

/**
 * A "proxy" schema can be created before the decorated schema exists.
 *
 * This allows e.g. recursive schemas.
 */
abstract class CfSchema_Neutral_ProxyBase implements CfSchema_NeutralInterface {

  /**
   * @var CfSchemaInterface
   */
  private $decorated;

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): CfSchemaInterface {
    return $this->decorated
      ?? $this->decorated = $this->doGetDecorated();
  }

  /**
   * @return CfSchemaInterface
   */
  abstract protected function doGetDecorated(): CfSchemaInterface;
}
