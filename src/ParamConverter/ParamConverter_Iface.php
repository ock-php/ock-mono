<?php
declare(strict_types=1);

namespace Drupal\cu\ParamConverter;

use Drupal\cu\Util\UiUtil;

class ParamConverter_Iface extends ParamConverterBase {

  public const TYPE = 'cu:interface';

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults) {

    $interface = str_replace('.', '\\', $value);

    if (!UiUtil::interfaceNameIsValid($interface)) {
      return FALSE;
    }

    // At this point, $interface looks like a valid class name. But it could still
    // be a non-existing interface, and possibly something ridiculously long.
    // Avoid interface_exists(), because autoload can have side effects.
    return $interface;
  }
}
