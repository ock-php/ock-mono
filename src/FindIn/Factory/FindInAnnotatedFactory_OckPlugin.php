<?php

declare(strict_types=1);

namespace Donquixote\Ock\FindIn\Factory;

use Donquixote\Ock\Attribute\Plugin\OckPlugin;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\FormulaFactory\Formula_FormulaFactory_StaticMethod;
use Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactory_Class;
use Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactory_StaticMethod;
use Donquixote\Ock\MetadataList\MetadataListInterface;
use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\Util\ReflectionUtil;

/**
 * @template-implements FindInAnnotatedFactoryInterface<string, Plugin[]>
 */
class FindInAnnotatedFactory_OckPlugin implements FindInAnnotatedFactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function findInAnnotatedClass(\ReflectionClass $reflectionClass, MetadataListInterface $metadata): \Iterator {
    if (!$metadata->count(OckPlugin::class)) {
      return;
    }

    $formula = new Formula_ValueFactory_Class($reflectionClass->getName());

    yield from self::formulaBuildPluginss(
      $formula,
      $reflectionClass,
      $metadata);
  }

  /**
   * {@inheritdoc}
   */
  public function findInAnnotatedMethod(\ReflectionMethod $reflectionMethod, MetadataListInterface $metadata): \Iterator {
    $class = ReflectionUtil::methodGetReturnClass($reflectionMethod);

    if (!is_a($class, FormulaInterface::class, TRUE)) {
      $formula = Formula_ValueFactory_StaticMethod::fromReflectionMethod($reflectionMethod);
      $reflectionClass = new \ReflectionClass($class);
    }
    else {
      $formula = Formula_FormulaFactory_StaticMethod::fromReflectionMethod($reflectionMethod);
      $reflectionClass = $reflectionMethod->getDeclaringClass();
    }

    yield from self::formulaBuildPluginss(
      $formula,
      $reflectionClass,
      $metadata);
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \ReflectionClass $reflectionClass
   * @param \Donquixote\Ock\MetadataList\MetadataListInterface $annotations
   *
   * @return \Iterator|Plugin[][]
   */
  private static function formulaBuildPluginss(FormulaInterface $formula, \ReflectionClass $reflectionClass, MetadataListInterface $annotations): \Iterator {

    $pluginTypeNames = ReflectionUtil::reflectionClassGetInterfaceNames($reflectionClass);

    /**
     * @psalm-suppress UnnecessaryVarAnnotation
     * @var OckPlugin $annotation
     * See https://youtrack.jetbrains.com/issue/WI-63230.
     */
    foreach ($annotations->getInstances(OckPlugin::class) as $annotation) {
      $id = $annotation->getId();
      $plugin = new Plugin(
        $annotation->getLabel(),
        $annotation->getDescription(),
        $formula,
        $annotation->getOptions());

      if ($annotation->isDecorator()) {
        foreach ($pluginTypeNames as $type) {
          yield $type => [$id => $plugin];
        }
      }
      else {
        foreach ($pluginTypeNames as $type) {
          yield "decorator<$type>" => [$id => $plugin];
        }
      }
    }
  }

}
