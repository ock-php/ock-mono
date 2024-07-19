<?php

declare(strict_types=1);

namespace Drupal\service_discovery_test;

use Ock\DependencyInjection\Attribute\Service;

#[Service]
class ServiceAtlas {

  public function __construct(
    public readonly ExampleService $exampleService,
  ) {}

}
