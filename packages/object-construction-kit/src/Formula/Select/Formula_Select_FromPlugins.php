<?php

declare(strict_types=1);

namespace Ock\Ock\Formula\Select;

use Ock\Ock\Plugin\Plugin;
use Ock\Ock\Text\TextInterface;

class Formula_Select_FromPlugins implements Formula_SelectInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Plugin\Plugin[] $plugins
   * @param \Ock\Ock\Text\TextInterface[] $groupLabels
   */
  public function __construct(
    private readonly array $plugins,
    private readonly array $groupLabels = [],
  ) {
    Plugin::validate(...array_values($plugins));
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionsMap(): array {
    $map = [];
    foreach ($this->plugins as $id => $plugin) {
      $parts = explode('.', $id, 2);
      $map[$id] = isset($parts[1]) ? $parts[0] : '';
    }
    return $map;
  }

  /**
   * {@inheritdoc}
   */
  public function groupIdGetLabel(int|string $groupId): ?TextInterface {
    return $this->groupLabels[$groupId] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {
    return ($this->plugins[$id] ?? NULL)?->getLabel();
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    return isset($this->plugins[$id]);
  }

}
