<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Tests;

use PHPUnit\Framework\Attributes\After;

trait ImmutableObjectsTrait {

  /**
   * Immutable objects by object id.
   *
   * @var array<int, object>
   */
  private array $immutableObjects = [];

  /**
   * Clones of the immutable objects.
   *
   * @var array<int, object>
   */
  private array $immutableObjectsBackup = [];

  /**
   * Registers an object as being immutable.
   *
   * The test will fail if the object has been modified.
   *
   * @param object $object
   *   Immutable object.
   */
  protected function registerImmutableObject(object $object): void {
    $id = spl_object_id($object);
    $this->immutableObjects[$id] = $object;
    $this->immutableObjectsBackup[$id] = clone $object;
  }

  /**
   * Runs after the main test method. Checks modifications in objects.
   */
  #[After]
  public function tearDownCheckImmutableObjects(): void {
    foreach ($this->immutableObjects as $id => $object) {
      $this->assertEquals($this->immutableObjectsBackup[$id], $object);
    }
  }

}
