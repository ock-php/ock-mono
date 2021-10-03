<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\AnnotatedFactories;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Ock\Discovery\FactoryVisitor\FactoryVisitorInterface;
use Donquixote\Ock\FindIn\Factory\FindInAnnotatedFactoryInterface;
use Donquixote\Ock\MetadataList\MetadataListInterface;

class AnnotatedFactories_Class implements AnnotatedFactoriesInterface {

  /**
   * @var \ReflectionClass
   */
  private $reflectionClass;

  /**
   * @var MetadataListInterface
   */
  private MetadataListInterface $metadata;

  /**
   * Constructor.
   *
   * @param \ReflectionClass $reflectionClass
   * @param \Donquixote\Ock\MetadataList\MetadataListInterface $metadata
   */
  public function __construct(\ReflectionClass $reflectionClass, MetadataListInterface $metadata) {
    $this->reflectionClass = $reflectionClass;
    $this->metadata = $metadata;
  }

  /**
   * {@inheritdoc}
   */
  public function accept(FactoryVisitorInterface $visitor): void {
    $visitor->visitAnnotatedClass(
      $this->reflectionClass,
      $this->metadata);
  }

  /**
   * {@inheritdoc}
   */
  public function find(FindInAnnotatedFactoryInterface $findInAnnotatedFactory): \Iterator {
    return $findInAnnotatedFactory->findInAnnotatedClass(
      $this->reflectionClass,
      $this->metadata);
  }

}
