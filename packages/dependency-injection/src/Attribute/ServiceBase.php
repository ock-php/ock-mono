<?php
declare(strict_types=1);

namespace Ock\DependencyInjection\Attribute;

/**
 * Base class for service declaration attribute.
 *
 * The service id is taken from the class name, or the method return type.
 *
 * @see \Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute
 */
abstract class ServiceBase {

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
   * @param bool $public
   *   (default) TRUE to make this service public.
   *   FALSE to make it private.
   */
  public function __construct(
    public readonly ?string $serviceId = null,
    public readonly ?string $serviceIdSuffix = null,
    public readonly ?string $target = null,
    public readonly bool $abstract = false,
    public readonly bool $public = true,
  ) {}

}
