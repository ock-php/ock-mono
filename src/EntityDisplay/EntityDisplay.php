<?php

namespace Drupal\renderkit8\EntityDisplay;

use Donquixote\Cf\Schema\CfSchema;
use Drupal\renderkit8\Context\EntityContext;
use Drupal\renderkit8\Util\UtilBase;

final class EntityDisplay extends UtilBase {

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function schema($entityType = NULL, $bundle = NULL) {

    if (NULL === $entityType) {
      return CfSchema::iface(EntityDisplayInterface::class);
    }

    return CfSchema::iface(
      EntityDisplayInterface::class,
      EntityContext::get($entityType, $bundle));
  }
}
