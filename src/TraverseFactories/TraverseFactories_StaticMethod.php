<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\AnnotatedFactories;

use Donquixote\Ock\Discovery\FactoryVisitor\FactoryVisitorInterface;
use Donquixote\Ock\FindIn\Factory\FindInAnnotatedFactoryInterface;
use Donquixote\Ock\MetadataList\MetadataListInterface;

class AnnotatedFactories_StaticMethod implements AnnotatedFactoriesInterface {

  /**
   * @var \ReflectionMethod
   */
  private $reflectionMethod;

  /**
   * @var MetadataListInterface
   */
  private MetadataListInterface $metadata;

  /**
   * Constructor.
   *
   * @param \ReflectionMethod $reflectionMethod
   * @param \Donquixote\Ock\MetadataList\MetadataListInterface $metadata
   */
  public function __construct(\ReflectionMethod $reflectionMethod, MetadataListInterface $metadata) {
    $this->reflectionMethod = $reflectionMethod;
    $this->metadata = $metadata;
  }

  /**
   * {@inheritdoc}
   */
  public function accept(FactoryVisitorInterface $visitor): void {
    $visitor->visitAnnotatedMethod(
      $this->reflectionMethod,
      $this->metadata);
  }

  /**
   * {@inheritdoc}
   */
  public function find(FindInAnnotatedFactoryInterface $findInAnnotatedFactory): \Iterator {
    return $findInAnnotatedFactory->findInAnnotatedMethod(
      $this->reflectionMethod,
      $this->metadata);
  }

}
