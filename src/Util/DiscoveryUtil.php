<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Util;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ObCK\Discovery\AnnotatedFactory\AnnotatedFactory;
use Donquixote\ObCK\Translator\Translator;
use Donquixote\ReflectionKit\ParamToValue\ParamToValue_ObjectsMatchType;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

final class DiscoveryUtil extends UtilBase {

  /**
   * @return \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface
   */
  public static function getParamToValue(): ParamToValueInterface {
    $services = [];
    $services[] = Translator::createPassthru();
    return new ParamToValue_ObjectsMatchType($services);
  }

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return \Iterator<\ReflectionClass>
   *   Format: $[$file] = $class
   */
  public static function findReflectionClasses(ClassFilesIAInterface $classFilesIA) {

    $classFilesIA = $classFilesIA->withRealpathRoot();

    foreach ($classFilesIA as $file => $class) {

      try {
        $reflClass = new \ReflectionClass($class);
      }
      catch (\ReflectionException $e) {
        // @todo Log non-existing class.
        continue;
      }

      if ($file !== $reflClass->getFileName()) {
        // It seems like this class is defined elsewhere.
        continue;
      }

      yield $reflClass;
    }
  }

  /**
   * @param \ReflectionClass $reflClass
   * @param string $annotationTagName
   *
   * @return \Iterator<\Donquixote\ObCK\Discovery\AnnotatedFactory\AnnotatedFactory>
   */
  public static function classFindAnnotatedFactories(
    \ReflectionClass $reflClass,
    string $annotationTagName
  ) {

    if (1
      && $reflClass->isInstantiable()
      && FALSE !== ($classDoc = $reflClass->getDocComment())
      && FALSE !== strpos($classDoc, '@' . $annotationTagName)
    ) {
      yield AnnotatedFactory::createFromClass($reflClass);
    }

    foreach ($reflClass->getMethods(\ReflectionMethod::IS_STATIC) as $reflectionMethod) {
      if (1
        && FALSE !== ($methodDoc = $reflectionMethod->getDocComment())
        && FALSE !== strpos($methodDoc, '@' . $annotationTagName)
      ) {
        yield AnnotatedFactory::createFromStaticMethod($reflectionMethod);
      }
    }
  }

}
