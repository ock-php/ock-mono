<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricFruit;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Attribute\Service;

/**
 * One public service to have other services as dependencies.
 *
 * This prevents the other (private) services from being forgotten when the
 * container is compiled.
 */
#[Service]
class FruitAtlas {

  public function __construct(
    #[GetParametricService('apple')]
    public readonly ParametricFruit $apple,
    #[GetParametricService('pear')]
    public readonly ParametricFruitJuice $pearJuice,
    #[GetParametricService('cherry')]
    public readonly OrchardInterface $cherryOrchard,
  ) {}

}
