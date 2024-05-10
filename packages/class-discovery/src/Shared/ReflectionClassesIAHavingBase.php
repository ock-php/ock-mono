<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Shared;

/**
 * Shared base class on top of the trait.
 *
 * Extend this if you want the methods, but no access to the private property.
 *
 * @see ReflectionClassesIAHavingTrait
 */
abstract class ReflectionClassesIAHavingBase {

  use ReflectionClassesIAHavingTrait;

}
