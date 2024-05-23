<?php
declare(strict_types=1);

namespace Drupal\ock\UI\ParamConverter;

use Drupal\ock\Attribute\DI\ServiceTags;
use Drupal\ock\Util\UiUtil;
use Ock\DID\Attribute\Service;

#[Service(self::class)]
#[ServiceTags(['paramconverter'])]
class ParamConverter_Iface extends ParamConverterBase {

  public const TYPE = 'ock:interface';

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults): array|bool|string {

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
