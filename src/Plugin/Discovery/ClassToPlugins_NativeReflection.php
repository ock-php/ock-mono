<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Discovery;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Discovery\DocToAnnotations\DocToAnnotations;
use Donquixote\Ock\Discovery\DocToAnnotations\DocToAnnotationsInterface;
use Donquixote\Ock\Exception\PluginListException;
use Donquixote\Ock\Formula\FormulaFactory\Formula_FormulaFactory_StaticMethod;
use Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactory_Class;
use Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactory_StaticMethod;
use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Util\DocUtil;

class ClassToPlugins_NativeReflection implements ClassToPluginsInterface {

  const KEYS_TO_REMOVE = [
    0 => TRUE, 'id' => TRUE,
    1 => TRUE, 'label' => TRUE,
    'description' => TRUE,
  ];

  /**
   * @var \Donquixote\Ock\Discovery\DocToAnnotations\DocToAnnotationsInterface
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
   * @param \Donquixote\Ock\Discovery\DocToAnnotations\DocToAnnotationsInterface $docToAnnotations
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

    try {
      $reflectionClass = new \ReflectionClass($class);
    }
    catch (\ReflectionException $e) {
      throw new PluginListException($e->getMessage(), 0, $e);
    }
    if ($reflectionClass->isInterface() || $reflectionClass->isTrait()) {
      return [];
    }

    // Cause an error if the class is defined elsewhere.
    require_once $file;

    return $this->reflectionClassGetPluginss($reflectionClass);
  }

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Donquixote\Ock\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   */
  private function reflectionClassGetPluginss(\ReflectionClass $reflectionClass): array {

    /** @var \Donquixote\Ock\Plugin\Plugin[][] $pluginss */
    $pluginss = [];

    if (!$reflectionClass->isAbstract()) {
      $pluginss = $this->reflectionClassGetClassLevelPluginss($reflectionClass);
    }

    foreach ($reflectionClass->getMethods() as $methodReflection) {

      if (0
        || !$methodReflection->isStatic()
        || $methodReflection->isAbstract()
        || $methodReflection->isConstructor()
      ) {
        continue;
      }

      foreach ($this->staticMethodGetPluginss($methodReflection) as $type => $definitions) {
        foreach ($definitions as $id => $definition) {
          $pluginss[$type][$id] = $definition;
        }
      }
    }

    return $pluginss;
  }

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Donquixote\Ock\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   */
  private function reflectionClassGetClassLevelPluginss(\ReflectionClass $reflectionClass): array {

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
   * @return \Donquixote\Ock\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   */
  private function staticMethodGetPluginss(\ReflectionMethod $method): array {

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
        return self::formulaFactoryGetPluginss($method, $annotations);
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
   * @return \Donquixote\Ock\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   */
  private static function formulaFactoryGetPluginss(
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
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
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
        Text::tOrNull($annotation['label'] ?? $annotation[1] ?? NULL) ?? Text::s($id),
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
    $interfaces = $reflectionClass->getInterfaceNames();
    if ($reflectionClass->isInterface()) {
      $interfaces[] = $reflectionClass->getName();
    }
    return $interfaces;
  }

}
