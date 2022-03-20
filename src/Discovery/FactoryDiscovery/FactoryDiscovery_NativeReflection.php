<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\FactoryDiscovery;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\Ock\Discovery\FactoryVisitor\FactoryVisitorInterface;
use Spiral\Attributes\ReaderInterface;

class FactoryDiscovery_NativeReflection extends FactoryDiscoveryBase {

  /**
   * @var \Spiral\Attributes\ReaderInterface
   */
  private $reader;

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   * @param \Spiral\Attributes\ReaderInterface $reader
   */
  public function __construct(ClassFilesIAInterface $classFilesIA, ReaderInterface $reader) {
    parent::__construct($classFilesIA);
    $this->reader = $reader;
  }

  /**
   * @param string $file
   * @param string $class
   * @param \Donquixote\Ock\Discovery\FactoryVisitor\FactoryVisitorInterface $visitor
   */
  protected function visitClassFile(string $file, string $class, FactoryVisitorInterface $visitor): void {
    try {
      $rc = new \ReflectionClass($class);
    }
    catch (\ReflectionException $e) {
      $visitor->reportException($e);
      return;
    }
    $metadata = [];
    foreach ($this->reader->getClassMetadata($rc) as $object) {
      $metadata[] = $object;
    }
    if ($metadata) {
      $visitor->visitAnnotatedClass($rc, $metadata);
    }
    foreach ($rc->getMethods() as $rm) {
      $metadata = [];
      foreach ($this->reader->getFunctionMetadata($rm) as $object) {
        $metadata[] = $object;
      }
      if ($metadata) {
        $visitor->visitAnnotatedMethod($rm, $metadata);
      }
    }
  }

}
