<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\FactoryVisitor;

use Donquixote\Ock\MetadataList\MetadataListInterface;

class FactoryVisitor_CollectIncarnatorPartials implements FactoryVisitorInterface {

  public function reset(): void {
    // TODO: Implement reset() method.
  }

  public function reportException(\Exception $e): void {
    // TODO: Implement reportException() method.
  }

  public function visitAnnotatedClass(\ReflectionClass $class, MetadataListInterface $metadata): void {
    // TODO: Implement visitAnnotatedClass() method.
  }

  public function visitAnnotatedMethod(\ReflectionMethod $method, MetadataListInterface $metadata): void {
    // TODO: Implement visitAnnotatedMethod() method.
  }

}
