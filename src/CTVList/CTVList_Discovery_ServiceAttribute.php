<?php

declare(strict_types = 1);

namespace Donquixote\DID\CTVList;

use Donquixote\ClassDiscovery\Shared\ReflectionClassesIAHavingBase;
use Donquixote\DID\Attribute\Parameter\CallServiceMethodWithArguments;
use Donquixote\DID\Attribute\Parameter\CallServiceWithArguments;
use Donquixote\DID\Attribute\ServiceDefinitionAttributeInterface;
use Donquixote\DID\Callback\CurryCall;
use Donquixote\DID\Callback\CurryConstruct;
use Donquixote\DID\ContainerToValue\ContainerToValue_CallableCall;
use Donquixote\DID\ContainerToValue\ContainerToValue_Construct;
use Donquixote\DID\ContainerToValue\ContainerToValue_ServiceId;
use Donquixote\DID\ContainerToValue\ContainerToValueInterface;
use Donquixote\DID\Exception\DiscoveryException;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Donquixote\DID\Util\AttributesUtil;
use Donquixote\DID\Util\MessageUtil;
use Donquixote\DID\Attribute\Parameter\GetArgument;

class CTVList_Discovery_ServiceAttribute extends ReflectionClassesIAHavingBase implements CTVListInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ParamToCTV\ParamToCTVInterface $paramToCTV
   */
  public function __construct(
    private readonly ParamToCTVInterface $paramToCTV,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getCTVs(): array {
    $ctvs = [];
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->itReflectionClasses() as $reflectionClass) {
      /** @var \Donquixote\DID\Attribute\ServiceDefinitionAttributeInterface[] $serviceAttributes */
      $serviceAttributes = AttributesUtil::getAll($reflectionClass, ServiceDefinitionAttributeInterface::class);
      if ($serviceAttributes) {
        foreach ($serviceAttributes as $serviceAttribute) {
          $serviceId = $serviceAttribute->reflectorGetServiceId($reflectionClass);
          $ctv = $this->onClass(
            $reflectionClass,
            $serviceAttribute->isParametricService(),
          );
          $ctvs[$serviceId] = $ctv;
        }
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        /** @var \Donquixote\DID\Attribute\ServiceDefinitionAttributeInterface[] $serviceAttributes */
        $serviceAttributes = AttributesUtil::getAll($reflectionMethod, ServiceDefinitionAttributeInterface::class);
        if ($serviceAttributes) {
          foreach ($serviceAttributes as $serviceAttribute) {
            $serviceId = $serviceAttribute->reflectorGetServiceId($reflectionMethod);
            $ctv = $this->onMethod(
              $reflectionClass,
              $reflectionMethod,
              $serviceAttribute->isParametricService(),
            );
            $ctvs[$serviceId] = $ctv;
          }
        }
      }
    }
    return $ctvs;
  }

  /**
   * @param \ReflectionClass $reflectionClass
   * @param bool $isCallableService
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  private function onClass(\ReflectionClass $reflectionClass, bool $isCallableService): ContainerToValueInterface {
    if (!$reflectionClass->isInstantiable()) {
      throw new DiscoveryException(sprintf(
        'Class %s is not instantiable.',
        $reflectionClass->getName(),
      ));
    }
    $parameters = $reflectionClass->getConstructor()?->getParameters() ?? [];
    if (!$isCallableService) {
      $argCTVs = $this->buildArgCTVs($parameters);
      return new ContainerToValue_Construct(
        $reflectionClass->getName(),
        $argCTVs,
      );
    }
    $argCTVs = $this->buildCallableArgCTVs($parameters, $curryArgNames, $callableArgCTVs);
    return CurryConstruct::ctv(
      $reflectionClass->getName(),
      $argCTVs,
      $curryArgNames,
      $callableArgCTVs,
    );
  }

  /**
   * @param \ReflectionClass $reflectionClass
   * @param \ReflectionMethod $reflectionMethod
   * @param bool $isCallableService
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  private function onMethod(\ReflectionClass $reflectionClass, \ReflectionMethod $reflectionMethod, bool $isCallableService): ContainerToValueInterface {
    if (!$reflectionMethod->isStatic()) {
      throw new DiscoveryException(sprintf(
        'Method %s is not static.',
        MessageUtil::formatReflector($reflectionMethod),
      ));
    }
    $parameters = $reflectionClass->getConstructor()?->getParameters();
    if (!$isCallableService) {
      $argCTVs = $this->buildArgCTVs($reflectionMethod->getParameters());
      return ContainerToValue_CallableCall::createFixed(
        [$reflectionClass->getName(), $reflectionMethod->getName()],
        $argCTVs,
      );
    }
    $argCTVs = $this->buildCallableArgCTVs($parameters, $curryArgNames, $callableArgCTVs);
    return CurryCall::ctv(
      [$reflectionClass->getName(), $reflectionMethod->getName()],
      $argCTVs,
      $curryArgNames,
      $callableArgCTVs,
    );
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return list<\Donquixote\DID\ContainerToValue\ContainerToValueInterface>
   *
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  private function buildArgCTVs(array $parameters): array {
    $argCTVs = [];
    foreach ($parameters as $parameter) {
      $ctv = $this->paramToCTV->paramGetCTV($parameter);
      if ($ctv === NULL) {
        throw new DiscoveryException(sprintf(
          'Cannot resolve parameter %s for container-to-value.',
          MessageUtil::formatReflector($parameter),
        ));
      }
      $argCTVs[$parameter->getName()] = $ctv;
    }
    returN $argCTVs;
  }

  /**
   * @param \ReflectionParameter[] $parameters
   * @param array|null $curryArgsMap
   * @param array|null $callableArgCTVs
   *
   * @return list<\Donquixote\DID\ContainerToValue\ContainerToValueInterface|null>
   *
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  private function buildCallableArgCTVs(array $parameters, ?array &$curryArgsMap, ?array &$callableArgCTVs): array {
    $argCTVs = [];
    foreach ($parameters as $parameter) {
      $ctv = $this->paramToCTV->paramGetCTV($parameter);
      $argCTVs[$parameter->getName()] = $ctv;
      if ($ctv !== NULL) {
        continue;
      }
      if ($attribute = AttributesUtil::getSingle($parameter, GetArgument::class)) {
        $curryArgsMap[$parameter->getName()] = $attribute->position;
      }
      elseif ($attribute = AttributesUtil::getSingle($parameter, CallServiceMethodWithArguments::class)) {
        $callableArgCTVs[$attribute->getName()] = CurryCall::ctvMethodCall(
          new ContainerToValue_ServiceId($attribute->serviceId),
          $attribute->method,
          [],
          $attribute->forwardArgsMap,
        );
      }
      elseif ($attribute = AttributesUtil::getSingle($parameter, CallServiceWithArguments::class)) {
        $callableArgCTVs[$attribute->getName()] = CurryCall::ctv(
          new ContainerToValue_ServiceId($attribute->paramGetServiceId($parameter)),
          $attribute->method,
          [],
          $attribute->forwardArgsMap,
        );
      }
      else {
        throw new DiscoveryException(sprintf(
          'Cannot resolve parameter %s for container-to-value, not even with arguments.',
          MessageUtil::formatReflector($parameter),
        ));
      }
    }
    return $argCTVs;
  }

}
