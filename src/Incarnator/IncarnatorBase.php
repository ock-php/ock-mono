<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Context\CfContextInterface;

abstract class IncarnatorBase implements IncarnatorInterface {

  /**
   * @var string
   */
  private string $cacheId;

  /**
   * @var \Donquixote\Ock\Context\CfContextInterface|null
   */
  private $context;

  /**
   * Constructor.
   *
   * @param string $cache_id
   */
  public function __construct(string $cache_id) {
    $this->cacheId = $cache_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheId(): string {
    return $this->cacheId;
  }

  /**
   * {@inheritdoc}
   */
  public function withContext(?CfContextInterface $context): self {
    $instance = clone $this;
    $instance->context = $context;
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getContext(): ?CfContextInterface {
    return $this->context;
  }

}
