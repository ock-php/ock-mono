<?php
declare(strict_types=1);

namespace Donquixote\Cf\Discovery\AnnotatedFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\Cf\Util\ReflectionUtil;

class AnnotatedFactory implements AnnotatedFactoryInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @var string
   */
  private $docComment;

  /**
   * @var string[]
   */
  private $returnTypeNames;

  /**
   * @var string|null
   */
  private $definitionValue;

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactoryInterface
   */
  public static function createFromClass(\ReflectionClass $reflectionClass): AnnotatedFactoryInterface {
    return new AnnotatedFactory_Class($reflectionClass);
  }

  /**
   * @param \ReflectionMethod $method
   *
   * @return self
   */
  public static function createFromStaticMethod(\ReflectionMethod $method): AnnotatedFactory {

    $returnTypeNames = ReflectionUtil::functionGetReturnTypeNames($method);

    $definitionValue = $method->getDeclaringClass()->getName() . '::' . $method->getName();

    return new self(
      new CallbackReflection_StaticMethod($method),
      // Convert false to '' (empty string).
      (string) $method->getDocComment(),
      $returnTypeNames,
      $definitionValue);
  }

  /**
   * @param callable $callable
   *
   * @return \Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactoryInterface|null
   *
   * @throws \ReflectionException
   *   Function or method does not exist.
   * @throws \Exception
   *   Malformed callable definition.
   */
  public static function fromCallable(callable $callable): ?AnnotatedFactoryInterface {

    if (NULL !== $reflFunction = ReflectionUtil::callableGetReflectionFunction(
      $callable)
    ) {

      if ($reflFunction instanceof \ReflectionMethod) {
        if ($reflFunction->isStatic()) {
          return self::createFromStaticMethod($reflFunction);
        }
      }

      $docComment = $reflFunction->getDocComment();
      $returnTypeNames = ReflectionUtil::functionGetReturnTypeNames($reflFunction);
    }
    else {
      $docComment = '';
      $returnTypeNames = [];
    }

    $callback = CallbackUtil::callableGetCallback($callable);

    if (NULL === $callback) {
      return NULL;
    }

    return new self(
      $callback,
      $docComment,
      $returnTypeNames,
      NULL);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string $docComment
   * @param string[] $returnTypeNames
   * @param string|null $definitionValue
   */
  public function __construct(
    CallbackReflectionInterface $callback,
    string $docComment,
    array $returnTypeNames,
    ?string $definitionValue
  ) {
    $this->callback = $callback;
    $this->docComment = $docComment;
    $this->returnTypeNames = $returnTypeNames;
    $this->definitionValue = $definitionValue;
  }

  /**
   * {@inheritdoc}
   */
  public function createDefinition(string $prefix): ?array {

    if (NULL === $this->definitionValue) {
      return NULL;
    }

    return [$prefix . '_factory' => $this->definitionValue];
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
    return $this->docComment;
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnTypeNames(): array {
    return $this->returnTypeNames;
  }

}
