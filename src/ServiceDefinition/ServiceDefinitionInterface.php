<?php

declare(strict_types = 1);

namespace Donquixote\DID\ServiceDefinition;

interface ServiceDefinitionInterface {

  /**
   * Gets the service id.
   *
   * @return string
   */
  public function getId(): string;

  /**
   * Gets the class or interface of the service.
   *
   * If the factory is also provided, the class will be only for informational
   * purposes.
   *
   * @return class-string
   */
  public function getClass(): string;

  /**
   * Gets a factory that returns an instance of the service.
   *
   * @return callable|null
   */
  public function getFactory(): ?callable;

}
