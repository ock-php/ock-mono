<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Compiler;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument;
use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Parametric\Placeholder_GetArgValue;
use Ock\DependencyInjection\Parametric\Placeholder_GetParametricService;
use Ock\DependencyInjection\Parametric\PlaceholderInterface;
use Ock\DependencyInjection\ServiceDefinitionUtil;
use Ock\Reflection\ParameterReflection;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass;
use Symfony\Component\DependencyInjection\Definition;
use function Ock\ReflectorAwareAttributes\get_attributes;

/**
 * Inserts placeholders for parametric arguments.
 */
class InsertParametricArgumentsPlaceholdersPass extends AbstractRecursivePass {

  /**
   * {@inheritdoc}
   */
  protected function processValue(mixed $value, bool $isRoot = false): mixed {
    if ($value instanceof Definition
      // Child definitions are problematic, due to the way their values are
      // copied to the real definitions.
      // Just skip them to avoid trouble.
      && !$value instanceof ChildDefinition
    ) {
      $this->processDefinition($value);
    }
    return parent::processValue($value, $isRoot);
  }

  /**
   * Alters a definition if it has parametric parameter attributes.
   *
   * @param \Symfony\Component\DependencyInjection\Definition $definition
   */
  protected function processDefinition(Definition $definition): void {
    $reflector = ServiceDefinitionUtil::getFactoryReflection($definition);
    if ($reflector === null) {
      return;
    }
    $parameters = $reflector->getParameters();
    if (!$parameters) {
      return;
    }
    $arguments = $definition->getArguments();
    foreach ($reflector->getParameters() as $parameter) {
      $placeholder = $this->createArgumentPlaceholder($parameter);
      if ($placeholder === NULL) {
        continue;
      }
      if (isset($arguments[$parameter->getPosition()])
        || isset($arguments['$' . $parameter->getName()])
      ) {
        // There is already an argument for this parameter.
        throw new \RuntimeException(
          sprintf(
            'An argument already exists for %s.',
            $parameter->getDebugName(),
          )
        );
      }
      $definition->setArgument($parameter->getPosition(), $placeholder);
    }
  }

  /**
   * Creates a placeholder argument.
   *
   * @param \Ock\Reflection\ParameterReflection $parameter
   *
   * @return \Ock\DependencyInjection\Parametric\PlaceholderInterface|null
   */
  protected function createArgumentPlaceholder(ParameterReflection $parameter): ?PlaceholderInterface {
    foreach (get_attributes($parameter, GetParametricArgument::class) as $attribute) {
      return $this->createParametricArgumentPlaceholder($attribute, $parameter);
    }
    foreach (get_attributes($parameter, GetParametricService::class) as $attribute) {
      return $this->createParametricServicePlaceholder($attribute, $parameter);
    }
    return NULL;
  }

  /**
   * Creates a placeholder argument.
   *
   * @param \Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument $attribute
   * @param \Ock\Reflection\ParameterReflection $parameter
   *
   * @return \Ock\DependencyInjection\Parametric\PlaceholderInterface
   */
  protected function createParametricArgumentPlaceholder(GetParametricArgument $attribute, ParameterReflection $parameter): PlaceholderInterface {
    if ($attribute->delta !== null) {
      $delta = $attribute->delta;
    }
    elseif ($parameter->getPosition() === 0) {
      $delta = 0;
    }
    else {
      throw new \RuntimeException(sprintf(
        'The delta must be provided in attribute %s on %s, if this is not the first parameter.',
        \get_class($attribute),
        $parameter->getDebugName(),
      ));
    }
    return new Placeholder_GetArgValue($delta);
  }

  /**
   * Creates a placeholder argument.
   *
   * @param \Ock\DependencyInjection\Attribute\Parameter\GetParametricService $attribute
   * @param \Ock\Reflection\ParameterReflection $parameter
   *
   * @return \Ock\DependencyInjection\Parametric\PlaceholderInterface
   */
  protected function createParametricServicePlaceholder(GetParametricService $attribute, ParameterReflection $parameter): PlaceholderInterface {
    $class = $parameter->getParamClassName();
    if ($class === null) {
      throw new \RuntimeException(sprintf(
        'Parameter %s with #[%s] attribute must have a class-like parameter type.',
        $parameter->getDebugName(),
        \get_class($attribute),
      ));
    }
    $args = [];
    foreach ($attribute->args as $key => $value) {
      if ($value instanceof GetParametricArgument) {
        if ($value->delta !== null) {
          $delta = $value->delta;
        }
        elseif ($key === 0) {
          $delta = 0;
        }
        else {
          throw new \RuntimeException(sprintf(
            'A delta must be provided in attribute #[%s] on %s, if this is not the first argument.',
            \get_class($attribute),
            $parameter->getDebugName()
          ));
        }
        $value = new Placeholder_GetArgValue($delta);
      }
      elseif (\is_int($value)) {
        $delta = $value;
        $value = new Placeholder_GetArgValue($delta);
      }
      elseif (!is_string($value)) {
        throw new \RuntimeException('Invalid argument value.');
      }
      $args[$key] = $value;
    }
    $parent_id = $class;
    return new Placeholder_GetParametricService($parent_id, $args);
  }

}
