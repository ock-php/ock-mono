<?php
declare(strict_types=1);

namespace Drupal\controller_attributes\PluginDeriver;

use Drupal\Component\Plugin\Derivative\DeriverInterface;

abstract class PluginDeriverBase implements DeriverInterface {

  /**
   * @var array|null
   */
  private ?array $derivatives = NULL;

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinition($derivative_id, mixed $base_plugin_definition): ?array {

    $derivatives = $this->getDerivativeDefinitions(
      $base_plugin_definition);

    return $derivatives[$derivative_id] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition): array {
    return $this->derivatives
      ??= $this->buildDerivativeDefinitions();
  }

  /**
   * @return array[]
   */
  abstract protected function buildDerivativeDefinitions(): array;
}
