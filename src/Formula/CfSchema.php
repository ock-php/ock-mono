<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Formula\Iface\CfSchema_IfaceWithContext;
use Donquixote\OCUI\Util\UtilBase;

final class CfSchema extends UtilBase {

  /**
   * @param string $interface
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public static function iface(string $interface, CfContextInterface $context = NULL): CfSchemaInterface {
    return CfSchema_IfaceWithContext::create($interface, $context);
  }

  /**
   * @param string $interface
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public static function ifaceOptional(string $interface, CfContextInterface $context = NULL): CfSchemaInterface {
    return CfSchema_IfaceWithContext::createOptional($interface, $context);
  }

  /**
   * @param string $interface
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public static function ifaceSequence(string $interface, CfContextInterface $context = NULL): CfSchemaInterface {
    return CfSchema_IfaceWithContext::createSequence($interface, $context);
  }

}
