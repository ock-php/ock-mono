<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Util\UtilBase;

final class CfSchema extends UtilBase {

  /**
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function iface(string $interface, CfContextInterface $context = NULL): CfSchemaInterface {
    return CfSchema_IfaceWithContext::create($interface, $context);
  }

  /**
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function ifaceOptional(string $interface, CfContextInterface $context = NULL): CfSchemaInterface {
    return CfSchema_IfaceWithContext::createOptional($interface, $context);
  }

  /**
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function ifaceSequence(string $interface, CfContextInterface $context = NULL): CfSchemaInterface {
    return CfSchema_IfaceWithContext::createSequence($interface, $context);
  }

}
