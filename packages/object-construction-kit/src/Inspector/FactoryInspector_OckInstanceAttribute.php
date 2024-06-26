<?php

declare(strict_types=1);

namespace Ock\Ock\Inspector;

use Ock\ClassDiscovery\Attribute\ReflectorAwareAttributeInterface;
use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Ock\ClassDiscovery\Reflection\ClassReflection;
use Ock\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Ock\ClassDiscovery\Reflection\MethodReflection;
use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\ClassDiscovery\Util\ReflectionTypeUtil;
use Ock\DID\Attribute\Parameter\GetServiceInterface;
use Ock\Helpers\Util\MessageUtil;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Ock\Ock\Contract\FormulaHavingInterface;
use Ock\Ock\Contract\LabelHavingInterface;
use Ock\Ock\Contract\NameHavingInterface;
use Ock\Ock\Exception\FormulaException;
use Ock\Ock\Exception\GroupFormulaDuplicateKeyException;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Formula\Group\GroupFormulaBuilder;
use Ock\Ock\Formula\Iface\Formula_Iface;
use Ock\Ock\OckPackage;
use Ock\Ock\Plugin\Plugin;
use Ock\Ock\Plugin\PluginDeclaration;
use Ock\Ock\Text\Text;
use Ock\Ock\Util\IdentifierLabelUtil;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Inspector that looks for instance factories.
 */
#[AutoconfigureTag(OckPackage::DISCOVERY_TAG_NAME)]
class FactoryInspector_OckInstanceAttribute implements FactoryInspectorInterface {

  /**
   * {@inheritdoc}
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator {
    $attribute = AttributesUtil::getSingle($reflector, OckPluginInstance::class);
    if ($attribute === null) {
      return;
    }
    if (!$reflector->isCallable()) {
      throw new MalformedDeclarationException(\sprintf(
        'Attribute %s is not allowed on %s.',
        get_class($attribute),
        $reflector->getDebugName(),
      ));
    }
    try {
      $resultClassReflection = $reflector->getReturnClass();
    }
    catch (\ReflectionException|\Error) {
      // Result class does not exist.
      // Ignore this declaration.
      return;
    }
    if ($resultClassReflection === null) {
      // At this point, $reflector must be a method.
      throw new MalformedDeclarationException(\sprintf(
        'Expected a class-like return type declaration on %s.',
        $reflector->getDebugName(),
      ));
    }
    $types = $resultClassReflection->getInterfaceNames();
    $builder = $this->buildGroupFormula($reflector->getParameters());
    if ($reflector instanceof ClassReflection) {
      $formula = $builder->construct($reflector->name);
    }
    elseif ($reflector instanceof MethodReflection) {
      if (!$reflector->isStatic()) {
        throw new MalformedDeclarationException(\sprintf(
          'This attribute is not allowed on non-static method %s. Consider making the method static.',
          MessageUtil::formatReflector($reflector),
        ));
      }
      $formula = $builder->call($reflector->getStaticCallableArray());
    }
    else {
      return;
    }

    $label = Text::tIf($attribute->label, $attribute->translate);
    $plugin = new Plugin($label, null, $formula, []);

    yield new PluginDeclaration($attribute->id, $types, $plugin);
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return \Ock\Ock\Formula\Group\GroupFormulaBuilder
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  private function buildGroupFormula(array $parameters): GroupFormulaBuilder {
    $builder = Formula::group();
    foreach ($parameters as $i => $parameter) {
      $attribute = AttributesUtil::getSingle($parameter, GetServiceInterface::class);
      if ($attribute !== NULL) {
        $serviceId = $attribute->paramGetServiceId($parameter);
        try {
          $builder->add(
            'service.' . $i,
            Text::i($i),
            Formula::serviceExpression($serviceId),
          );
        }
        catch (GroupFormulaDuplicateKeyException $e) {
          // Convert to unhandled exception, because this would be a programming
          // error within this package.
          throw new \RuntimeException(sprintf(
            'Unreachable code: Duplicate group formula key %s for parameter %s.',
            'service.' . $i,
            MessageUtil::formatReflector($parameter),
          ), 0, $e);
        }
        continue;
      }
      $name = AttributesUtil::requireSingle($parameter, NameHavingInterface::class)
        ->getName();
      $label = AttributesUtil::getSingle($parameter, LabelHavingInterface::class)
        ?->getLabel()
        ?? IdentifierLabelUtil::fromInterface($name);
      $formula = AttributesUtil::getSingle($parameter, FormulaHavingInterface::class)
        ?->getFormula()
        ?? new Formula_Iface(
          ReflectionTypeUtil::requireGetClassLikeType($parameter),
          $parameter->allowsNull(),
        );
      try {
        $builder->add($name, $label, $formula);
      }
      catch (FormulaException $e) {
        throw new MalformedDeclarationException(sprintf(
          'Problem on %s: %s.',
          MessageUtil::formatReflector($parameter),
          $e->getMessage(),
        ), 0, $e);
      }
      /** @var \Ock\ClassDiscovery\Attribute\ReflectorAwareAttributeInterface $attribute */
      foreach (AttributesUtil::getAll($parameter, ReflectorAwareAttributeInterface::class) as $attribute) {
        $attribute->setReflector($parameter);
      }
    }
    return $builder;
  }

}
