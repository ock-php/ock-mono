<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Renders the entity in a view mode depending on the entity type.
 *
 * This is currently not registered as a plugin, because it seems an uncommon
 * use case, and would require unnecessarily complex configuration.
 */
class EntityDisplay_ViewModeByType extends EntityDisplay_ViewModeBase {

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string[] $viewModesByType
   *   Format: $[$entity_type] = $view_mode
   * @param string|null $defaultViewModeName
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    private readonly array $viewModesByType,
    private readonly ?string $defaultViewModeName = NULL
  ) {
    parent::__construct($entityTypeManager);
  }

  /**
   * @param string $entityType
   *
   * @return string|null
   */
  protected function etGetViewMode(string $entityType): ?string {

    if (!empty($this->viewModesByType[$entityType])) {
      return $this->viewModesByType[$entityType];
    }
    elseif (NULL !== $this->defaultViewModeName) {
      return $this->defaultViewModeName;
    }
    else {
      return NULL;
    }
  }

}
