<?php

declare(strict_types=1);

namespace Donquixote\Ock\FindIn\ReflectionClass;

use Donquixote\Ock\FindIn\Factory\FindInAnnotatedFactoryInterface;
use Spiral\Attributes\Internal\FallbackAttributeReader;
use Spiral\Attributes\Internal\Instantiator\NamedArgumentsInstantiator;
use Spiral\Attributes\Internal\NativeAttributeReader;
use Spiral\Attributes\ReaderInterface;

/**
 * @template T
 *
 * @template-implements FindInReflectionClassInterface<T>
 */
class FindInReflectionClass_AnnotatedFactory implements FindInReflectionClassInterface {

  /**
   * @var \Spiral\Attributes\ReaderInterface
   */
  private $reader;

  /**
   * @var \Donquixote\Ock\FindIn\Factory\FindInAnnotatedFactoryInterface<T, object>
   */
  private $findInAnnotatedFactory;

  private ?string $metadataClass;

  /**
   * Constructor.
   *
   * @param \Spiral\Attributes\ReaderInterface $reader
   * @param \Donquixote\Ock\FindIn\Factory\FindInAnnotatedFactoryInterface<T, object> $findInAnnotatedFactory
   */
  public function __construct(ReaderInterface $reader, FindInAnnotatedFactoryInterface $findInAnnotatedFactory) {
    $this->reader = $reader;
    $this->findInAnnotatedFactory = $findInAnnotatedFactory;
    $this->metadataClass = $findInAnnotatedFactory->getMetadataClass();
  }

  public static function create(FindInAnnotatedFactoryInterface $findInAnnotatedFactory) {
    $instantiator = new NamedArgumentsInstantiator();
    $reader = NativeAttributeReader::isAvailable()
      ? new NativeAttributeReader($instantiator)
      : new FallbackAttributeReader($instantiator);
    return new self($reader, $findInAnnotatedFactory);
  }

  /**
   * {@inheritdoc}
   */
  public function find(\ReflectionClass $reflectionClass): \Iterator {

    $metadata = [];
    foreach ($this->reader->getClassMetadata($reflectionClass) as $object) {
      if ($this->metadataClass === NULL || $object instanceof $this->metadataClass) {
        $metadata[] = $object;
      }
    }
    if ($metadata) {
      yield from $this->findInAnnotatedFactory->findInAnnotatedClass($reflectionClass, $metadata);
    }

    foreach ($reflectionClass->getMethods() as $reflectionMethod) {
      $metadata = [];
      foreach ($this->reader->getFunctionMetadata($reflectionMethod) as $object) {
        $metadata[] = $object;
      }
      if ($metadata) {
        yield from $this->findInAnnotatedFactory->findInAnnotatedMethod(
          $reflectionMethod,
          $metadata);
      }
    }
  }

}
