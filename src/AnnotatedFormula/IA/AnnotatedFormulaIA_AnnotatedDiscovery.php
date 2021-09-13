<?php

declare(strict_types=1);

namespace Donquixote\Ock\AnnotatedFormula\IA;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\Ock\AnnotatedFormula\AnnotatedFormula;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Discovery\DocToAnnotations\DocToAnnotationsInterface;
use Donquixote\Ock\Formula\FormulaFactory\Formula_FormulaFactory_StaticMethod;
use Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactory_Class;
use Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactory_StaticMethod;
use Donquixote\Ock\Util\DocUtil;

class AnnotatedFormulaIA_AnnotatedDiscovery implements AnnotatedFormulaIAInterface {

  /**
   * @var \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  private ClassFilesIAInterface $classFilesIA;

  /**
   * @var \Donquixote\Ock\Discovery\DocToAnnotations\DocToAnnotationsInterface
   */
  private DocToAnnotationsInterface $docToAnnotations;

  /**
   * @var string
   */
  private string $tagName;

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   * @param \Donquixote\Ock\Discovery\DocToAnnotations\DocToAnnotationsInterface $docToAnnotations
   * @param string $tagName
   */
  public function __construct(ClassFilesIAInterface $classFilesIA, DocToAnnotationsInterface $docToAnnotations, string $tagName) {
    $this->classFilesIA = $classFilesIA;
    $this->docToAnnotations = $docToAnnotations;
    $this->tagName = $tagName;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->classFilesIA as $file => $class) {
      if (FALSE !== strpos(file_get_contents($file), '@' . $this->tagName)) {
        try {
          $reflectionClass = new \ReflectionClass($class);
        }
        catch (\ReflectionException $e) {
          // @todo Log this.
          continue;
        }
        if (!$reflectionClass->isInterface() && !$reflectionClass->isTrait()) {
          // Cause an error if the class is defined elsewhere.
          require_once $file;
          yield from $this->checkClass($reflectionClass);
        }
      }
    }
  }

  /**
   * @param \ReflectionClass $class
   *
   * @return \Iterator<\Donquixote\Ock\AnnotatedFormula\AnnotatedFormulaInterface>
   */
  private function checkClass(\ReflectionClass $class): \Iterator {

    // Find annotations on the class directly.
    if (!$class->isAbstract()
      && (FALSE !== $classDoc = $class->getDocComment())
      && ($annotations = $this->docToAnnotations->docGetAnnotations($classDoc))
    ) {
      $types = self::classGetInterfaces($class);
      if ($types) {
        $formula = new Formula_ValueFactory_Class($class->getName());
        yield from self::buildAnnotatedFormulas($types, $annotations, $formula);
      }
    }

    // Find annotations on static factory methods.
    foreach ($class->getMethods() as $method) {
      if ($method->isStatic()
        && !$method->isAbstract()
        && !$method->isConstructor()
        && FALSE !== ($docComment = $method->getDocComment())
        && ($annotations = $this->docToAnnotations->docGetAnnotations($docComment))
        && ($types = self::methodGetReturnTypes($method))
      ) {
        if (isset($types[FormulaInterface::class])) {
          // The method can return a formula.
          $types = self::classGetInterfaces($method->getDeclaringClass());
          $formula = Formula_FormulaFactory_StaticMethod::fromReflectionMethod($method);
        }
        else {
          // The method won't return a formula.
          $formula = Formula_ValueFactory_StaticMethod::fromReflectionMethod($method);
        }

        yield from self::buildAnnotatedFormulas($types, $annotations, $formula);
      }
    }
  }

  /**
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return string[]
   *   Format: $[$interface] = $interface
   */
  private static function methodGetReturnTypes(\ReflectionMethod $reflectionMethod): array {

    if (FALSE === $docComment = $reflectionMethod->getDocComment()) {
      return [];
    }

    if ([] === $returnTypeClassNames = DocUtil::docGetReturnTypeClassNames($docComment, $reflectionMethod->getDeclaringClass()->getName())) {
      return [];
    }

    $returnTypeInterfaceNames = [];
    foreach ($returnTypeClassNames as $returnTypeClassName) {
      try {
        $returnTypeReflectionClass = new \ReflectionClass($returnTypeClassName);
      }
      catch (\ReflectionException $e) {
        continue;
      }
      if ($returnTypeReflectionClass->isTrait()) {
        continue;
      }
      $returnTypeInterfaceNames += self::classGetInterfaces($returnTypeReflectionClass);
    }

    return $returnTypeInterfaceNames;
  }

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return string[]
   *   Format: $[$interface] = $interface
   */
  private static function classGetInterfaces(\ReflectionClass $reflectionClass): array {
    $types = $reflectionClass->getInterfaceNames();
    if ($reflectionClass->isInterface()) {
      $types[] = $reflectionClass->getName();
    }
    return array_combine($types, $types);
  }

  /**
   * @param string[] $types
   * @param array $annotations
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *
   * @return iterable<\Donquixote\Ock\AnnotatedFormula\AnnotatedFormulaInterface>
   */
  private static function buildAnnotatedFormulas(array $types, array $annotations, FormulaInterface $formula): iterable {
    foreach ($types as $type) {
      foreach ($annotations as $annotation) {
        yield new AnnotatedFormula($type, $annotation, $formula);
      }
    }
  }

}
