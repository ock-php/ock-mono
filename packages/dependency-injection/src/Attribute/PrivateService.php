<?php
declare(strict_types=1);

namespace Ock\DependencyInjection\Attribute;

/**
 * Marks a class or method as a private service.
 *
 * The service id is taken from the class name, or the method return type.
 *
 * @see \Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute
 */
#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS|\Attribute::IS_REPEATABLE)]
final class PrivateService extends ServiceBase {

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
   * @param bool $abstract
   *   TRUE to make this service abstract.
   */
  public function __construct(
    ?string $serviceId = null,
    ?string $serviceIdSuffix = null,
    ?string $target = null,
    bool $abstract = false,
  ) {
    parent::__construct(
      $serviceId,
      $serviceIdSuffix,
      $target,
      $abstract,
      false,
    );
  }

}
