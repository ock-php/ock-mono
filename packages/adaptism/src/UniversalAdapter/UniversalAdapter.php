<?php
declare(strict_types=1);

namespace Ock\Adaptism\UniversalAdapter;

use Ock\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(public: true)]
class UniversalAdapter implements UniversalAdapterInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Adaptism\SpecificAdapter\SpecificAdapterInterface $specificAdapter
   */
  public function __construct(
    private readonly SpecificAdapterInterface $specificAdapter,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter = null,
  ): ?object {
    if ($adaptee instanceof $resultType) {
      return $adaptee;
    }
    return $this->specificAdapter->adapt(
      $adaptee,
      $resultType,
      $universalAdapter ?? $this,
    );
  }
}
