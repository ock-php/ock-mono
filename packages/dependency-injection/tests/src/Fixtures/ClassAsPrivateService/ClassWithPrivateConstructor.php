<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ClassAsPrivateService;

/**
 * A class with private constructor should be ignored.
 */
class ClassWithPrivateConstructor {

  /**
   * Constructor.
   */
  private function __construct() {}

}
