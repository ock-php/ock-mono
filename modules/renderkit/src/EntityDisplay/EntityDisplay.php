<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Drupal\renderkit\Context\EntityContext;
use Drupal\renderkit\Util\UtilBase;

final class EntityDisplay extends UtilBase {

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function formula(string $entityType = NULL, string $bundle = NULL): FormulaInterface {

    if (NULL === $entityType) {
      return Formula::iface(EntityDisplayInterface::class);
    }

    return Formula::iface(
      EntityDisplayInterface::class,
      EntityContext::get($entityType, $bundle));
  }

}
