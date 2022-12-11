<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Select;

use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\Text\TextInterface;

class Formula_Select_FromPlugins implements Formula_SelectInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Plugin[] $plugins
   * @param \Donquixote\Ock\Text\TextInterface[] $groupLabels
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
    $sortme = array_keys($this->groupLabels + $this->plugins);
    sort($sortme);
    $groupId = '';
    $map = [];
    foreach ($sortme as $id) {
      if (isset($this->groupLabels[$id])) {
        $groupId = $id;
      }
      if (isset($this->plugins[$id])) {
        $map[$id] = $groupId;
      }
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
