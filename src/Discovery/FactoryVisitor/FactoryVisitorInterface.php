<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\FactoryVisitor;

use Donquixote\Ock\MetadataList\MetadataListInterface;

interface FactoryVisitorInterface {

  /**
   * Resets all collected data.
   */
  public function reset(): void;

  /**#
   * @param \Exception $e
   */
  public function reportException(\Exception $e): void;

  /**
   * Visits an annotated class.
   *
   * @param \ReflectionClass $class
   * @param \Donquixote\Ock\MetadataList\MetadataListInterface $metadata
   *
   * @throws \Exception
   */
  public function visitAnnotatedClass(\ReflectionClass $class, MetadataListInterface $metadata): void;

  /**
   * Visits an annotated method.
   *
   * @param \ReflectionMethod $method
   * @param \Donquixote\Ock\MetadataList\MetadataListInterface $metadata
   *
   * @throws \Exception
   */
  public function visitAnnotatedMethod(\ReflectionMethod $method, MetadataListInterface $metadata): void;

}
