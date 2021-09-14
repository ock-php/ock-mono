<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\AnnotatedFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Ock\Util\ReflectionUtil;

class AnnotatedFactory_StaticMethod implements AnnotatedFactoryInterface {

  /**
   * @var \ReflectionMethod
   */
  private $method;

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @var string[]
   */
  private $returnTypeNames;

  /**
   * @param \ReflectionMethod $method
   *
   * @return self
   */
  public static function createFromStaticMethod(\ReflectionMethod $method): AnnotatedFactory_StaticMethod {

    $returnTypeNames = ReflectionUtil::functionGetReturnTypeNames($method);

    return new self(
      $method,
      $returnTypeNames);
  }

  /**
   * @param \ReflectionMethod $method
   * @param string[] $returnTypeNames
   */
  public function __construct(
    \ReflectionMethod $method,
    array $returnTypeNames
  ) {
    $this->method = $method;
    $this->callback = new CallbackReflection_StaticMethod($method);
    $this->returnTypeNames = $returnTypeNames;
  }

  /**
   * @return \ReflectionMethod
   */
  public function getReflectionMethod(): \ReflectionMethod {
    return $this->method;
  }

  /**
   * {@inheritdoc}
   */
  public function createDefinition(string $prefix): ?array {
    return [
      $prefix . '_factory' => $this->method->getDeclaringClass()->getName()
        . '::' .  $this->method->getName(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCallback(): CallbackReflectionInterface {
    return $this->callback;
  }

  /**
   * {@inheritdoc}
   */
  public function getDocComment(): string {
    return $this->method->getDocComment();
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnTypeNames(): array {
    return $this->returnTypeNames;
  }

}
