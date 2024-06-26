<?php

declare(strict_types = 1);

namespace Ock\DID\Attribute\Parameter;

use Ock\ClassDiscovery\Attribute\ReflectorAwareAttributeInterface;
use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Util\ReflectionTypeUtil;
use Ock\Helpers\Util\MessageUtil;

/**
 * Treats the service as a callable.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class GetParametricService implements ReflectorAwareAttributeInterface {

  /**
   * Constructor.
   *
   * @param string|null $virtualServiceId
   *   Id of the "virtual service", that is, the object being returned from the
   *   callable.
   * @param array $args
   *   Fixed argument values.
   */
  public function __construct(
    private ?string $virtualServiceId = NULL,
    public readonly array $args = [],
  ) {}

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return string
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public function paramGetServiceId(\ReflectionParameter $parameter): string {
    if ($this->virtualServiceId !== NULL) {
      return $this->virtualServiceId;
    }
    $type = ReflectionTypeUtil::getClassLikeType($parameter);
    if ($type === NULL) {
      throw new MalformedDeclarationException(sprintf(
        'No service id and no class-like type were provided on %s.',
        MessageUtil::formatReflector($parameter),
      ));
    }
    return 'get.' . $type;
  }

  /**
   * @return string
   */
  public function getCallableServiceId(): string {
    if ($this->virtualServiceId === NULL) {
      throw new \RuntimeException('Attribute was not initialized. Please call setReflector().');
    }
    return 'get.' . $this->virtualServiceId;
  }

  /**
   * @param \Reflector $reflector
   *
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public function setReflector(\Reflector $reflector): void {
    if ($this->virtualServiceId !== NULL) {
      return;
    }
    if (!$reflector instanceof \ReflectionParameter) {
      throw new \InvalidArgumentException(sprintf(
        'Unexpected reflector type %s.',
        get_class($reflector),
      ));
    }
    $type = ReflectionTypeUtil::getClassLikeType($reflector);
    if ($type === NULL) {
      throw new MalformedDeclarationException(sprintf(
        'No service id and no class-like type were provided on %s.',
        MessageUtil::formatReflector($reflector),
      ));
    }
    $this->virtualServiceId = $type;
  }

}
