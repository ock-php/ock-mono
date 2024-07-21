<?php

declare(strict_types=1);

namespace Drupal\service_discovery\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Wrapper for a compiler pass.
 *
 * This protects a pass from being detected with instanceof check.
 */
class NeutrallyDecoratingPass implements CompilerPassInterface {

  /**
   * Constructor.
   *
   * @param \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface $pass
   */
  public function __construct(
    private readonly CompilerPassInterface $pass,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function process(ContainerBuilder $container): void {
    $this->pass->process($container);
  }

}
