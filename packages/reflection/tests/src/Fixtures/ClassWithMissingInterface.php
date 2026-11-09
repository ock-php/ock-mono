<?php

declare(strict_types = 1);

namespace Ock\Reflection\Tests\Fixtures;

/**
 * Class whose interface does not exist.
 *
 * Autoloading this class throws an \Error.
 *
 * @see \Ock\Reflection\Tests\ClassReflectionTest::testMissingDependency()
 *
 * This file is excluded from PhpStan analysis, see phpstan.neon.
 */
class ClassWithMissingInterface implements MissingInterface {}
