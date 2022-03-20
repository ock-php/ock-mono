<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\FactoryDiscovery;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\Ock\Discovery\FactoryVisitor\FactoryVisitorInterface;

abstract class FactoryDiscoveryBase implements FactoryDiscoveryInterface {

  /**
   * @var \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  private $classFilesIA;

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   */
  public function __construct(ClassFilesIAInterface $classFilesIA) {
    $this->classFilesIA = $classFilesIA;
  }

  /**
   * {@inheritdoc}
   */
  public function visitAll(FactoryVisitorInterface $visitor): void {
    foreach ($this->classFilesIA->withRealpathRoot() as $file => $class) {
      $this->visitClassFile($file, $class, $visitor);
    }
  }

  /**
   * @param string $file
   * @param string $class
   * @param \Donquixote\Ock\Discovery\FactoryVisitor\FactoryVisitorInterface $visitor
   */
  abstract protected function visitClassFile(string $file, string $class, FactoryVisitorInterface $visitor): void;

}
