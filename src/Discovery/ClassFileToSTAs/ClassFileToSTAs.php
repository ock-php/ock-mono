<?php
declare(strict_types=1);

namespace Donquixote\Cf\Discovery\ClassFileToSTAs;

use Donquixote\Cf\Discovery\FactoryToSTA\FactoryToSTA;
use Donquixote\Cf\Discovery\FactoryToSTA\FactoryToSTAInterface;
use Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactories;
use Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface;
use Donquixote\FactoryReflection\ClassToFactories\ClassToFactories;
use Donquixote\FactoryReflection\FunctionToReturnType\FunctionToReturnType_Chain;
use Donquixote\ReflectionKit\ContextFinder\ContextFinder_PhpTokenParser;

class ClassFileToSTAs implements ClassFileToSTAsInterface {

  /**
   * @var \Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface
   */
  private $classFileFactories;

  /**
   * @var \Donquixote\Cf\Discovery\FactoryToSTA\FactoryToSTAInterface
   */
  private $factoryToSTA;

  /**
   * @return \Donquixote\Cf\Discovery\ClassFileToSTAs\ClassFileToSTAsInterface
   */
  public static function create(): ClassFileToSTAsInterface {
    $functionToReturnType = FunctionToReturnType_Chain::create();
    return new self(
      new ClassFileToFactories(
        ClassToFactories::create($functionToReturnType),
        new ContextFinder_PhpTokenParser()),
      new FactoryToSTA());
  }

  /**
   * @param ClassFileToFactoriesInterface $classFileFactories
   * @param \Donquixote\Cf\Discovery\FactoryToSTA\FactoryToSTAInterface $factoryToSTA
   */
  public function __construct(
    ClassFileToFactoriesInterface $classFileFactories,
    FactoryToSTAInterface $factoryToSTA
  ) {
    $this->classFileFactories = $classFileFactories;
    $this->factoryToSTA = $factoryToSTA;
  }

  /**
   * {@inheritdoc}
   */
  public function classFileGetPartials(string $class, string $fileRealpath): array {

    $factories = $this->classFileFactories->classFileGetFactories(
      $class,
      $fileRealpath);

    $partials = [];
    foreach ($factories as $factory) {
      if (null !== $partial = $this->factoryToSTA->factoryGetPartial($factory)) {
        $partials[] = $partial;
      }
    }

    return $partials;
  }

}
