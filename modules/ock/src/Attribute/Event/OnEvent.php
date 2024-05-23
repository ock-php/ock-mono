<?php

declare(strict_types = 1);

namespace Drupal\ock\Attribute\Event;

#[\Attribute(\Attribute::TARGET_METHOD)]
class OnEvent {

  public function __construct(
    private readonly string $eventName,
    private readonly ?int $priority = NULL,
  ) {}

  public function getEventName(): string {
    return $this->eventName;
  }

  public function getPriority(): ?int {
    return $this->priority;
  }

}
