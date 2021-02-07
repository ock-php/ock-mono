<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\Discovery\ClassFileToDefinitions;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Discovery\DocToAnnotations\DocToAnnotations;
use Donquixote\OCUI\Discovery\DocToAnnotations\DocToAnnotationsInterface;
use Donquixote\OCUI\Util\DefinitionUtil;
use Donquixote\OCUI\Util\DocUtil;

class ClassFileToDefinitions_NativeReflection implements ClassFileToDefinitionsInterface {

  /**
   * @var \Donquixote\OCUI\Discovery\DocToAnnotations\DocToAnnotationsInterface
   */
  private $docToAnnotations;

  /**
   * @var string
   */
  private $tagName;

  /**
   * @param string $tagName
   *
   * @return self
   */
  public static function create(string $tagName): ClassFileToDefinitions_NativeReflection {
    return new self(
      DocToAnnotations::create($tagName),
      $tagName);
  }

  /**
   * @param \Donquixote\OCUI\Discovery\DocToAnnotations\DocToAnnotationsInterface $docToAnnotations
   * @param string $tagName
   */
  public function __construct(DocToAnnotationsInterface $docToAnnotations, string $tagName) {
    $this->docToAnnotations = $docToAnnotations;
    $this->tagName = $tagName;
  }

  /**
   * {@inheritdoc}
   */
  public function classFileGetDefinitions(string $class, string $file): array {

    if (FALSE === $php = file_get_contents($file)) {
      return [];
    }

    if (FALSE === strpos($php, '@' . $this->tagName)) {
      return [];
    }

    try {
      $reflectionClass = new \ReflectionClass($class);
    }
    catch (\ReflectionException $e) {
      // Class does not exist.
      // @todo Log an error.
      return [];
    }

    if ($reflectionClass->isInterface() || $reflectionClass->isTrait()) {
      return [];
    }

    // Cause an error if the class is defined elsewhere.
    require_once $file;

    return $this->reflectionClassGetDefinitions($reflectionClass);
  }

  /**
   * Reads all plugin definitions on a class and its static methods.
   *
   * @param \ReflectionClass $reflectionClass
   *   The class, as a ReflectionClass object.
   *
   * @return array[][]
   *   Plugin definitions that were found.
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  private function reflectionClassGetDefinitions(\ReflectionClass $reflectionClass): array {

    $definitionsByTypeAndId = [];

    if (!$reflectionClass->isAbstract()) {
      $definitionsByTypeAndId = $this->reflectionClassGetDefinitionsForClass($reflectionClass);
    }

    foreach ($reflectionClass->getMethods() as $methodReflection) {

      if (0
        || !$methodReflection->isStatic()
        || $methodReflection->isAbstract()
        || $methodReflection->isConstructor()
      ) {
        continue;
      }

      foreach ($this->staticMethodGetDefinitions($methodReflection) as $type => $definitions) {
        foreach ($definitions as $id => $definition) {
          $definitionsByTypeAndId[$type][$id] = $definition;
        }
      }
    }

    return $definitionsByTypeAndId;
  }

  /**
   * Reads plugin definitions on class/constructor level.
   *
   * Annotations are in the class doc, but the factory signature is coming from
   * the constructor method.
   *
   * @param \ReflectionClass $reflectionClass
   *   The class, as a ReflectionClass object.
   *
   * @return array[][]
   *   Plugin definitions that were found.
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition.
   */
  private function reflectionClassGetDefinitionsForClass(\ReflectionClass $reflectionClass): array {

    if (FALSE === $docComment = $reflectionClass->getDocComment()) {
      return [];
    }

    if ([] ===  $annotations = $this->docToAnnotations->docGetAnnotations($docComment)) {
      return [];
    }

    $stubDefinition = ['handler_class' => $reflectionClass->getName()];

    $pluginTypeNames = self::classGetPluginTypeNames($reflectionClass);

    if ([] === $pluginTypeNames) {
      return [];
    }

    $className = $reflectionClass->getShortName();

    $definitionsById = DefinitionUtil::buildDefinitionsById(
      $stubDefinition,
      $annotations,
      $className);

    return array_fill_keys($pluginTypeNames, $definitionsById);
  }

  /**
   * Reads plugin definitions from a specific static method.
   *
   * @param \ReflectionMethod $method
   *   A static method.
   *
   * @return array[][]
   *   Plugin definitions that were found.
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition.
   */
  private function staticMethodGetDefinitions(\ReflectionMethod $method): array {

    if (FALSE === $docComment = $method->getDocComment()) {
      return [];
    }

    if (!$annotations = $this->docToAnnotations->docGetAnnotations($docComment)) {
      return [];
    }

    if ([] === $returnTypeNames = $this->reflectionMethodGetReturnTypeNames($method)) {
      return [];
    }

    foreach ($returnTypeNames as $returnTypeName) {

      if (is_a($returnTypeName, FormulaInterface::class, TRUE)) {
        // The method returns a formula object.
        // The actual plugin type has to be determined elsewhere.
        return self::formulaFactoryGetDefinitions($method, $annotations);
      }
    }

    $name = $method->getDeclaringClass()->getName() . '::' . $method->getName();

    $definition = [
      'handler_factory' => $name,
    ];

    $definitionsById = DefinitionUtil::buildDefinitionsById(
      $definition,
      $annotations,
      $name);

    return array_fill_keys($returnTypeNames, $definitionsById);
  }

  /**
   * @param \ReflectionMethod $method
   *   A static method.
   * @param array[] $annotations
   *   E.g. [['id' => 'entityTitle', 'label' => 'Entity title'], ..]
   *
   * @return array[][]
   *   Plugin definitions that were found.
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition.
   */
  private static function formulaFactoryGetDefinitions(
    \ReflectionMethod $method,
    array $annotations
  ): array {

    $name = $method->getDeclaringClass()->getName() . '::' . $method->getName();

    $definition = [
      'formula_factory' => $name,
    ];

    $pluginTypeNames = self::classGetPluginTypeNames(
      $method->getDeclaringClass());

    $definitionsById = DefinitionUtil::buildDefinitionsById(
      $definition,
      $annotations,
      $name);

    // Multiply the definitions for all applicable plugin type names.
    return array_fill_keys($pluginTypeNames, $definitionsById);
  }

  /**
   * Reads return type names for a static method.
   *
   * @param \ReflectionMethod $reflectionMethod
   *   The method to analyse.
   *
   * @return string[]
   *   Format: $[$interface] = $interface.
   */
  private function reflectionMethodGetReturnTypeNames(\ReflectionMethod $reflectionMethod): array {

    if (FALSE === $docComment = $reflectionMethod->getDocComment()) {
      return [];
    }

    if ([] === $returnTypeClassNames = DocUtil::docGetReturnTypeClassNames($docComment, $reflectionMethod->getDeclaringClass()->getName())) {
      return [];
    }

    $returnTypeInterfaceNames = [];
    foreach ($returnTypeClassNames as $returnTypeClassName) {
      try {
        $reflClass = new \ReflectionClass($returnTypeClassName);
      }
      catch (\ReflectionException $e) {
        // Class does not exist.
        // @todo Log an error.
        continue;
      }
      if ($reflClass->isInterface()) {
        // Assume it is an interface.
        // @todo Include parent interfaces?
        $returnTypeInterfaceNames[] = $returnTypeClassName;
      }
      elseif (!$reflClass->isTrait()) {
        // Traits are not supported.
        // @todo Log an error.
        continue;
      }
      // Find the interfaces for the class.
      foreach (self::classGetPluginTypeNames($reflClass) as $interfaceName) {
        $returnTypeInterfaceNames[] = $interfaceName;
      }
    }

    // Multiply the definitions for all applicable plugin type names.
    return array_unique($returnTypeInterfaceNames);
  }

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return string[]
   *   Format: $[] = $interface
   */
  private static function classGetPluginTypeNames(\ReflectionClass $reflectionClass): array {

    if ($reflectionClass->isInterface()) {
      return [$reflectionClass->getName()];
    }

    $interfaces = $reflectionClass->getInterfaces();
    foreach ($interfaces as $interfaceName => $reflectionInterface) {
      if (!isset($interfaces[$interfaceName])) {
        continue;
      }
      foreach ($reflectionInterface->getInterfaceNames() as $nameToUnset) {
        unset($interfaces[$nameToUnset]);
      }
    }

    // Multiply the definitions for all applicable plugin type names.
    return array_keys($interfaces);
  }
}
