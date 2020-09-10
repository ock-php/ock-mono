<?php
declare(strict_types=1);

namespace Donquixote\Cf\Discovery;

use Donquixote\Cf\Discovery\FactoryToSTA\FactoryToSTA;
use Donquixote\Cf\Discovery\FactoryToSTA\FactoryToSTAInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactories;
use Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\FactoryReflection\FunctionToReturnType\FunctionToReturnType_Chain;
use Donquixote\ReflectionKit\ContextFinder\ContextFinder_PhpTokenParser;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class STADiscovery_X {

  /**
   * @var \Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface
   */
  private $classFileToFactories;

  /**
   * @var \Donquixote\Cf\Discovery\FactoryToSTA\FactoryToSTAInterface
   */
  private $factoryToSTA;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   */
  public static function create(ParamToValueInterface $paramToValue) {

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
   * @param \Donquixote\Cf\Discovery\FactoryToSTA\FactoryToSTAInterface $factoryToSTA
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
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
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
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
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

        if (null === $partialOrNull) {
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

    if (false === $docComment = $factory->getDocComment()) {
      return false;
    }

    return self::docCommentHasArglessAnnotationName($docComment, 'STA');
  }

  /**
   * @param string $docComment
   * @param string $name
   *
   * @return bool
   */
  public static function docCommentHasArglessAnnotationName($docComment, $name): bool {

    if (false === strpos($docComment, '@' . $name)) {
      return false;
    }

    $pattern = ''
      . '~(' . '^/\*\*\h+' . '|' . '\v\h*(\*\h+|)' . ')@'
      . preg_quote($name, '~')
      . '(\(\)|)' . '(\h*\v|\h*\*/$)~';

    if (!preg_match($pattern, $docComment)) {
      return false;
    }

    return true;
  }
}
