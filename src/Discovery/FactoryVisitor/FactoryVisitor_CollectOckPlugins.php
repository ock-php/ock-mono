<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\FactoryVisitor;

use Donquixote\Ock\Attribute\Plugin\OckPlugin;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\FormulaFactory\Formula_FormulaFactory_StaticMethod;
use Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactory_Class;
use Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactory_StaticMethod;
use Donquixote\Ock\MetadataList\MetadataListInterface;
use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\Util\ReflectionUtil;

class FactoryVisitor_CollectOckPlugins implements FactoryVisitorInterface {

  /**
   * @var string
   */
  private $idPrefix = '';

  /**
   * @var \Donquixote\Ock\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   */
  private array $pluginss = [];

  /**
   * @var \Exception[]
   */
  private array $exceptions = [];

  /**
   * Sets a prefix to prepend to each id.
   *
   * @param string $prefix
   */
  public function setIdPrefix(string $prefix): void {
    $this->idPrefix = $prefix;
  }

  /**
   * @return \Donquixote\Ock\Plugin\Plugin[][]
   */
  public function getPluginss(): array {
    return $this->pluginss;
  }

  /**
   * @return \Exception[]
   */
  public function getExceptions(): array {
    return $this->exceptions;
  }

  public function reset(): void {
    $this->pluginss = [];
  }

  public function reportException(\Exception $e): void {
    $this->exceptions[] = $e;
  }

  /**
   * {@inheritdoc}
   */
  public function visitAnnotatedClass(\ReflectionClass $class, MetadataListInterface $metadata): void {
    $annotations = $metadata->getInstances(OckPlugin::class);
    if (!$annotations) {
      return;
    }
    $formula = new Formula_ValueFactory_Class($class->getName());

    $this->visitFormula(
      $formula,
      $class,
      $annotations);
  }

  /**
   * {@inheritdoc}
   */
  public function visitAnnotatedMethod(\ReflectionMethod $method, MetadataListInterface $metadata): void {
    $annotations = $metadata->getInstances(OckPlugin::class);
    if (!$annotations) {
      return;
    }
    $class = ReflectionUtil::methodGetReturnClass($method);

    if (!is_a($class, FormulaInterface::class, TRUE)) {
      $formula = Formula_ValueFactory_StaticMethod::fromReflectionMethod($method);
      $reflectionClass = new \ReflectionClass($class);
    }
    else {
      $formula = Formula_FormulaFactory_StaticMethod::fromReflectionMethod($method);
      $reflectionClass = $method->getDeclaringClass();
    }

    $this->visitFormula(
      $formula,
      $reflectionClass,
      $annotations);
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \ReflectionClass $class
   * @param \Donquixote\Ock\Attribute\Plugin\OckPlugin[] $annotations
   */
  private function visitFormula(
    FormulaInterface $formula,
    \ReflectionClass $class,
    array $annotations
  ): void {

    $pluginTypeNames = ReflectionUtil::reflectionClassGetInterfaceNames($class);

    foreach ($annotations as $annotation) {
      $id = $this->idPrefix . $annotation->getId();
      $plugin = new Plugin(
        $annotation->getLabel(),
        $annotation->getDescription(),
        $formula,
        $annotation->getOptions());

      if (!$annotation->isDecorator()) {
        foreach ($pluginTypeNames as $type) {
          $this->pluginss[$type][$id] = $plugin;
        }
      }
      else {
        foreach ($pluginTypeNames as $type) {
          $this->pluginss["decorator<$type>"][$id] = $plugin;
        }
      }
    }
  }

}
