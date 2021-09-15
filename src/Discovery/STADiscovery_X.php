<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactories;
use Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\FactoryReflection\FunctionToReturnType\FunctionToReturnType_Chain;
use Donquixote\Ock\Discovery\FactoryToSTA\FactoryToSTA;
use Donquixote\Ock\Discovery\FactoryToSTA\FactoryToSTAInterface;
use Donquixote\Ock\Util\AnnotationUtil;
use Donquixote\ReflectionKit\ContextFinder\ContextFinder_PhpTokenParser;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class STADiscovery_X {

  /**
   * @var \Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface
   */
  private $classFileToFactories;

  /**
   * @var \Donquixote\Ock\Discovery\FactoryToSTA\FactoryToSTAInterface
   */
  private $factoryToSTA;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   */
  public static function create(ParamToValueInterface $paramToValue): self {

    $contextFinder = new ContextFinder_PhpTokenParser();
    $functionToReturnType = FunctionToReturnType_Chain::create();

    return new self(
      ClassFileToFactories::create(
        $contextFinder,
        $functionToReturnType),
      FactoryToSTA::createComposite(
        $paramToValue,
        $contextFinder,
        $functionToReturnType));
  }

  /**
   * @param \Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface $classFileToFactories
   * @param \Donquixote\Ock\Discovery\FactoryToSTA\FactoryToSTAInterface $factoryToSTA
   */
  public function __construct(
    ClassFileToFactoriesInterface $classFileToFactories,
    FactoryToSTAInterface $factoryToSTA
  ) {
    $this->classFileToFactories = $classFileToFactories;
    $this->factoryToSTA = $factoryToSTA;
  }

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   */
  public function classFilesIAGetPartials(ClassFilesIAInterface $classFilesIA): array {
    try {
      return $this->classFilesIADoGetPartials($classFilesIA);
    }
    catch (\Throwable $e) {
      return [];
    }
  }

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   */
  private function classFilesIADoGetPartials(ClassFilesIAInterface $classFilesIA): array {

    $partials = [];
    foreach ($classFilesIA->withRealpathRoot() as $fileRealpath => $class) {

      try {
        $factories = $this->classFileToFactories->classFileGetFactories($class, $fileRealpath);
      }
      catch (\Exception $e) {
        continue;
      }
      catch (\Throwable $e) {
        continue;
      }

      if (!$factories) {
        continue;
      }

      foreach ($factories as $factory) {

        if (!$this->factoryIsSTA($factory)) {
          continue;
        }

        try {
          $partialOrNull = $this->factoryToSTA->factoryGetPartial($factory);
        }
        catch (\Exception $e) {
          continue;
        }

        if (NULL === $partialOrNull) {
          continue;
        }

        $partials[] = $partialOrNull;
      }
    }

    return $partials;
  }

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return bool
   */
  private function factoryIsSTA(ReflectionFactoryInterface $factory): bool {

    if (FALSE === $docComment = $factory->getDocComment()) {
      return FALSE;
    }

    return AnnotationUtil::docCommentHasArglessAnnotationName($docComment, 'STA');
  }

}
