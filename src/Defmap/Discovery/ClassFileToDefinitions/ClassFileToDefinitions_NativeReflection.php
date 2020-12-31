<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\Discovery\ClassFileToDefinitions;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Discovery\DocToAnnotations\DocToAnnotations;
use Donquixote\Cf\Discovery\DocToAnnotations\DocToAnnotationsInterface;
use Donquixote\Cf\Util\DefinitionUtil;
use Donquixote\Cf\Util\DocUtil;

class ClassFileToDefinitions_NativeReflection implements ClassFileToDefinitionsInterface {

  /**
   * @var \Donquixote\Cf\Discovery\DocToAnnotations\DocToAnnotationsInterface
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
  public static function create($tagName): ClassFileToDefinitions_NativeReflection {
    return new self(
      DocToAnnotations::create($tagName),
      $tagName);
  }

  /**
   * @param \Donquixote\Cf\Discovery\DocToAnnotations\DocToAnnotationsInterface $docToAnnotations
   * @param string $tagName
   */
  public function __construct(DocToAnnotationsInterface $docToAnnotations, $tagName) {
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
   * @param \ReflectionClass $reflectionClass
   *
   * @return array[][]
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
   * @param \ReflectionClass $reflectionClass
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
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

    return DefinitionUtil::buildDefinitionsByTypeAndId(
      $pluginTypeNames,
      $definitionsById);
  }

  /**
   * @param \ReflectionMethod $method
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
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

      if (is_a($returnTypeName, CfSchemaInterface::class, TRUE)) {
        // The method returns a schema object.
        // The actual plugin type has to be determined elsewhere.
        return self::schemaFactoryGetDefinitions($method, $annotations);
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

    return DefinitionUtil::buildDefinitionsByTypeAndId(
      $returnTypeNames,
      $definitionsById);
  }

  /**
   * @param \ReflectionMethod $method
   * @param array[] $annotations
   *   E.g. [['id' => 'entityTitle', 'label' => 'Entity title'], ..]
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  private static function schemaFactoryGetDefinitions(
    \ReflectionMethod $method,
    array $annotations
  ): array {

    $name = $method->getDeclaringClass()->getName() . '::' . $method->getName();

    $definition = [
      'schema_factory' => $name,
    ];

    $pluginTypeNames = self::classGetPluginTypeNames(
      $method->getDeclaringClass());

    $definitionsById = DefinitionUtil::buildDefinitionsById(
      $definition,
      $annotations,
      $name);

    return DefinitionUtil::buildDefinitionsByTypeAndId(
      $pluginTypeNames,
      $definitionsById);
  }

  /**
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return string[]
   *   Format: $[$interface] = $interface
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
      if (class_exists($returnTypeClassName)) {
        // Find the interfaces for the class.
        foreach (self::classGetPluginTypeNames(new \ReflectionClass($returnTypeClassName)) as $interfaceName) {
          $returnTypeInterfaceNames[] = $interfaceName;
        }
      }
      else {
        // Assume it is an interface.
        $returnTypeInterfaceNames[] = $returnTypeClassName;
      }
    }

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

    return array_keys($interfaces);
  }
}
