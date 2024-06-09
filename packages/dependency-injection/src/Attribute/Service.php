<?php
declare(strict_types=1);

namespace Ock\DependencyInjection\Attribute;

/**
 * Marks a class or method as a service, public by default.
 *
 * The service id is taken from the class name, or the method return type.
 *
 * @see \Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute
 */
#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS|\Attribute::IS_REPEATABLE)]
final class Service extends ServiceBase {

}
