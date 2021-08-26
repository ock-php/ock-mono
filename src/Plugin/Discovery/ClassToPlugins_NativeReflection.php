<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\Discovery;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Discovery\DocToAnnotations\DocToAnnotations;
use Donquixote\ObCK\Discovery\DocToAnnotations\DocToAnnotationsInterface;
use Donquixote\ObCK\Formula\FormulaFactory\Formula_FormulaFactory_StaticMethod;
use Donquixote\ObCK\Formula\ValueFactory\Formula_ValueFactory_Class;
use Donquixote\ObCK\Formula\ValueFactory\Formula_ValueFactory_StaticMethod;
use Donquixote\ObCK\Plugin\Plugin;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Util\DocUtil;

class ClassToPlugins_NativeReflection implements ClassToPluginsInterface {

  const KEYS_TO_REMOVE = [
    TRUE, 'id' => TRUE,
    TRUE, 'label' => TRUE,
    'description' => TRUE,
  ];

  /**
   * @var \Donquixote\ObCK\Discovery\DocToAnnotations\DocToAnnotationsInterface
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
  public static function create(string $tagName): self {
    return new self(
      DocToAnnotations::create($tagName),
      $tagName);
  }

  /**
   * @param \Donquixote\ObCK\Discovery\DocToAnnotations\DocToAnnotationsInterface $docToAnnotations
   * @param string $tagName
   */
  public function __construct(DocToAnnotationsInterface $docToAnnotations, string $tagName) {
    $this->docToAnnotations = $docToAnnotations;
    $this->tagName = $tagName;
  }

  /**
   * {@inheritdoc}
   */
  public function classGetPluginss(string $class, string $file): array {

    if (FALSE === $php = file_get_contents($file)) {
      return [];
    }

    if (FALSE === strpos($php, '@' . $this->tagName)) {
      return [];
    }

    $reflectionClass = new \ReflectionClass($class);
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
   * @return \Donquixote\ObCK\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   */
  private function reflectionClassGetDefinitions(\ReflectionClass $reflectionClass): array {

    /** @var \Donquixote\ObCK\Plugin\Plugin[][] $definitionsByTypeAndId */
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
   * @return \Donquixote\ObCK\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   */
  private function reflectionClassGetDefinitionsForClass(\ReflectionClass $reflectionClass): array {

    if (FALSE === $docComment = $reflectionClass->getDocComment()) {
      return [];
    }

    if ([] === $annotations = $this->docToAnnotations->docGetAnnotations($docComment)) {
      return [];
    }

    $formula = new Formula_ValueFactory_Class($reflectionClass->getName());

    $pluginTypeNames = self::classGetPluginTypeNames($reflectionClass);

    if ([] === $pluginTypeNames) {
      return [];
    }

    return self::formulaBuildPluginss(
      $formula,
      $pluginTypeNames,
      $annotations);
  }

  /**
   * @param \ReflectionMethod $method
   *
   * @return \Donquixote\ObCK\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
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
        // The method returns a schema object.
        // The actual plugin type has to be determined elsewhere.
        return self::formulaFactoryGetDefinitions($method, $annotations);
      }
    }

    $formula = Formula_ValueFactory_StaticMethod::fromReflectionMethod($method);

    return self::formulaBuildPluginss(
      $formula,
      $returnTypeNames,
      $annotations);
  }

  /**
   * @param \ReflectionMethod $method
   * @param array[] $annotations
   *   E.g. [['id' => 'entityTitle', 'label' => 'Entity title'], ..]
   *
   * @return \Donquixote\ObCK\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   */
  private static function formulaFactoryGetDefinitions(
    \ReflectionMethod $method,
    array $annotations
  ): array {

    $formula = Formula_FormulaFactory_StaticMethod::fromReflectionMethod($method);

    $pluginTypeNames = self::classGetPluginTypeNames(
      $method->getDeclaringClass());

    if ([] === $pluginTypeNames) {
      return [];
    }

    return self::formulaBuildPluginss(
      $formula,
      $pluginTypeNames,
      $annotations);
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param string[] $pluginTypeNames
   * @param array[] $annotations
   *
   * @return array
   */
  private static function formulaBuildPluginss(FormulaInterface $formula, array $pluginTypeNames, array $annotations): array {

    $pluginss = [];
    foreach ($annotations as $annotation) {
      $id = $annotation['id'] ?? $annotation[0] ?? NULL;
      if ($id === NULL) {
        // @todo Log this.
        continue;
      }
      $plugin = new Plugin(
        Text::tOrNull($annotation['label'] ?? $annotation[1] ?? NULL),
        Text::tOrNull($annotation['description'] ?? NULL),
        $formula,
        array_diff_key($annotation, self::KEYS_TO_REMOVE));
      if (empty($annotation['decorator'])) {
        foreach ($pluginTypeNames as $type) {
          $pluginss[$type][$id] = $plugin;
        }
      }
      else {
        foreach ($pluginTypeNames as $type) {
          $pluginss["decorator<$type>"][$id] = $plugin;
        }
      }
    }

    return $pluginss;
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
      try {
        $returnTypeReflectionClass = new \ReflectionClass($returnTypeClassName);
      }
      catch (\ReflectionException $e) {
        continue;
      }
      if ($returnTypeReflectionClass->isTrait()) {
        continue;
      }
      foreach (self::classGetPluginTypeNames($returnTypeReflectionClass) as $interfaceName) {
        $returnTypeInterfaceNames[] = $interfaceName;
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
