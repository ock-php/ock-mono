<?php
declare(strict_types=1);

namespace Ock\DID\Attribute;

/**
 * Marks a class or method as a service.
 */
#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS|\Attribute::IS_REPEATABLE)]
abstract class ServiceDefinitionAttributeBase {

  /**
   * Constructor.
   *
   * @param string|null $serviceId
   *   The id, or NULL to use the interface name or the class name.
   * @param string|null $serviceIdSuffix
   *   Suffix to append to a service id.
   *   This allows to distinguish services that implement the same interface.
   * @param string|list<string>|false|null $alias
   *   One of:
   *     - A string, to use a specific alias.
   *     - A list of strings, to use multiple aliases.
   *     - FALSE, to not add any aliases.
   *     - NULL, to automatically determine aliases based on interfaces.
   */
  public function __construct(
    public readonly ?string $serviceId = NULL,
    public readonly ?string $serviceIdSuffix = NULL,
    public readonly string|array|false|null $alias = null,
  ) {}

  /**
   * @return bool
   */
  abstract public function isParametric(): bool;

}
