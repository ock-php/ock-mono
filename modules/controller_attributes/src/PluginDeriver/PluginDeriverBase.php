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
   * Gets the definition of a derivative plugin.
   *
   * @param string $derivative_id
   *   The derivative id. The id must uniquely identify the derivative within a
   *   given base plugin, but derivative ids can be reused across base plugins.
   * @param mixed $base_plugin_definition
   *   The definition of the base plugin from which the derivative plugin
   *   is derived. It is maybe an entire object or just some array, depending
   *   on the discovery mechanism.
   *
   * @return array|null
   *   The full definition array of the derivative plugin, typically a merge of
   *   $base_plugin_definition with extra derivative-specific information. NULL
   *   if the derivative doesn't exist.
   */
  public function getDerivativeDefinition($derivative_id, mixed $base_plugin_definition): ?array {

    $derivatives = $this->getDerivativeDefinitions(
      $base_plugin_definition);

    return $derivatives[$derivative_id] ?? NULL;
  }

  /**
   * Gets the definition of all derivatives of a base plugin.
   *
   * @param array $base_plugin_definition
   *   The definition array of the base plugin.
   *
   * @return array
   *   An array of full derivative definitions keyed on derivative id.
   *
   * @see getDerivativeDefinition()
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
