<?php

declare(strict_types = 1);

namespace Donquixote\DID\CTVList;

use Donquixote\ClassDiscovery\Shared\ReflectionClassesIAHavingBase;
use Donquixote\DID\Attribute\Parameter\GetArgument;
use Donquixote\DID\Attribute\ParametricService;
use Donquixote\DID\Callback\CurryConstruct;
use Donquixote\DID\ContainerToValue\ContainerToValue_CallableCall;
use Donquixote\DID\ContainerToValue\ContainerToValueInterface;
use Donquixote\DID\Exception\DiscoveryException;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Donquixote\DID\Util\AttributesUtil;
use Donquixote\CodegenTools\Util\MessageUtil;

class CTVList_Discovery_ParameterizedServiceAttribute extends ReflectionClassesIAHavingBase implements CTVListInterface {

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
      /** @var \Donquixote\DID\Attribute\ParametricService[] $serviceAttributes */
      $serviceAttributes = AttributesUtil::getAll($reflectionClass, ParametricService::class);
      if ($serviceAttributes) {
        $ctv = $this->onClass($reflectionClass);
        foreach ($serviceAttributes as $serviceAttribute) {
          $serviceId = $serviceAttribute->reflectorGetServiceId($reflectionClass);
          $ctvs[$serviceId] = $ctv;
        }
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        /** @var \Donquixote\DID\Attribute\ParametricService[] $serviceAttributes */
        $serviceAttributes = AttributesUtil::getAll($reflectionMethod, ParametricService::class);
        if ($serviceAttributes) {
          $ctv = $this->onMethod($reflectionClass, $reflectionMethod);
          foreach ($serviceAttributes as $serviceAttribute) {
            $serviceId = $serviceAttribute->reflectorGetServiceId($reflectionMethod);
            $ctvs[$serviceId] = $ctv;
          }
        }
      }
    }
    return $ctvs;
  }

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  private function onClass(\ReflectionClass $reflectionClass): ContainerToValueInterface {
    if (!$reflectionClass->isInstantiable()) {
      throw new DiscoveryException(sprintf(
        'Class %s is not instantiable.',
        $reflectionClass->getName(),
      ));
    }
    $argCTVs = [];
    $curryArgNames = [];
    foreach ($reflectionClass->getConstructor()?->getParameters() ?? [] as $parameter) {
      $attributes = AttributesUtil::getAll($parameter, GetArgument::class);
      if ($attributes !== []) {

      }
      $argCTVs[] = $this->paramToCTV->paramGetCTV($parameter);
    }
    return CurryConstruct::ctv(
      $reflectionClass->getName(),
      $argCTVs,
      $curryArgNames,
    );
  }

  /**
   * @param \ReflectionClass $reflectionClass
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  private function onMethod(\ReflectionClass $reflectionClass, \ReflectionMethod $reflectionMethod): ContainerToValueInterface {
    if (!$reflectionMethod->isStatic()) {
      throw new DiscoveryException(sprintf(
        'Method %s is not static.',
        MessageUtil::formatReflector($reflectionMethod),
      ));
    }
    $argCTVs = $this->buildArgCTVs($reflectionMethod->getParameters());
    return ContainerToValue_CallableCall::createFixed(
      [$reflectionClass->getName(), $reflectionMethod->getName()],
      $argCTVs,
    );
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface[]
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
      $argCTVs[] = $ctv;
    }
    return $argCTVs;
  }

}