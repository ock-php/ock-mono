<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Attribute;

/**
 * Marks a method as a self-adapter.
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final class SelfAdapter implements AdapterAttributeInterface {

  /**
   * Constructor.
   *
   * @param int|null $specifity
   */
  public function __construct(
    private readonly ?int $specifity = null,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): ?int {
    return $this->specifity;
  }

  /**
   * {@inheritdoc}
   */
  public function isSelfAdapter(): bool {
    return true;
  }

}
