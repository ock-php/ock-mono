<?php

declare(strict_types=1);

namespace Ock\Adaptism\Attribute;

abstract class AdapterAttributeBase {

  /**
   * Constructor.
   *
   * @param int|null $specifity
   */
  public function __construct(
    public readonly ?int $specifity = null,
  ) {}

}
