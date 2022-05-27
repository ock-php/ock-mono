<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Attribute\Parameter;

/**
 * Marks a parameter to expect a service from the container.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class GetService {

  /**
   * Constructor.
   *
   * @param string|null $id
   *   Id of the service, or NULL to use interface name.
   */
  public function __construct(
    private readonly ?string $id = NULL,
  ) {}

  public function getId(): ?string {
    return $this->id;
  }

}
