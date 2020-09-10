<?php
declare(strict_types=1);

namespace Donquixote\Cf\Discovery\AnnotatedFactoryIA;

use Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactory;
use Donquixote\Cf\Util\DiscoveryUtil;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

class AnnotatedFactoriesIA implements AnnotatedFactoriesIAInterface {

  /**
   * @var \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  private $classFilesIA;

  /**
   * @var string
   */
  private $needle;

  /**
   * @var string
   */
  private $needleRegex;

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   * @param string $annotationTagName
   */
  public function __construct(ClassFilesIAInterface $classFilesIA, $annotationTagName) {
    $this->classFilesIA = $classFilesIA;
    $this->needle = '@' . $annotationTagName;
    $this->needleRegex = '/' . preg_quote('@' . $annotationTagName, '/') . '\w/';
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {

    foreach (DiscoveryUtil::findReflectionClasses($this->classFilesIA) as $reflClass) {
      // "yield from" is not supported in PHP 5.6.
      foreach ($this->reflectionClassGetFactories($reflClass) as $factory) {
        yield $factory;
      }
    }
  }

  /**
   * @param \ReflectionClass $reflClass
   *
   * @return \Iterator|\Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactory[]
   */
  private function reflectionClassGetFactories(\ReflectionClass $reflClass): \Iterator {

    if (1
      && $reflClass->isInstantiable()
      && FALSE !== ($classDoc = $reflClass->getDocComment())
      && FALSE !== strpos($classDoc, $this->needle)
      && FALSE !== preg_match($this->needleRegex, $classDoc)
    ) {
      yield AnnotatedFactory::createFromClass($reflClass);
    }

    foreach ($reflClass->getMethods() as $reflectionMethod) {
      if (1
        && $reflectionMethod->isStatic()
        && FALSE !== ($methodDoc = $reflectionMethod->getDocComment())
        && FALSE !== strpos($methodDoc, $this->needle)
        && FALSE !== preg_match($this->needleRegex, $methodDoc)
      ) {
        yield AnnotatedFactory::createFromStaticMethod($reflectionMethod);
      }
    }
  }

}
