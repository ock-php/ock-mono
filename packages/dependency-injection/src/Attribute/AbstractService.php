<?php
declare(strict_types=1);

namespace Ock\DependencyInjection\Attribute;

/**
 * Marks a class or method as an abstract service, private by default.
 *
 * The service id is taken from the class name, or the method return type.
 *
 * @see \Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute
 */
#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS|\Attribute::IS_REPEATABLE)]
final class AbstractService extends ServiceBase {

  /**
   * Constructor.
   *
   * @param string|null $serviceId
   *   The id, or NULL to use the interface name or the class name.
   * @param string|null $serviceIdSuffix
   *   Suffix to append to a service id.
   *   This allows to distinguish services that implement the same interface.
   * @param string|null $target
   *   Alternative suffix to append to the service id, symfony style.
   *   This will be appended like a parameter name.
   * @param bool $public
   *   TRUE to make this service public.
   *   (default) FALSE to make it private.
   */
  public function __construct(
    ?string $serviceId = null,
    ?string $serviceIdSuffix = null,
    ?string $target = null,
    bool $public = false,
  ) {
    parent::__construct(
      $serviceId,
      $serviceIdSuffix,
      $target,
      true,
      $public,
    );
  }

}
