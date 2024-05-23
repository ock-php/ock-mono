<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\renderkit\Formula\Formula_EntityViewMode;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Attribute\Parameter\OckFormulaFromService;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Renders the entity with a view mode, but only if it has the expected type.
 */
class EntityDisplay_ViewModeOneType extends EntityDisplay_ViewModeBase {

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entityType
   * @param string $viewMode
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    private readonly string $entityType,
    private readonly string $viewMode
  ) {
    parent::__construct($entityTypeManager);
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $etDotViewMode
   *   A combination of entity type and view mode name.
   *
   * @return self
   */
  #[OckPluginInstance('viewMode', 'View mode')]
  public static function createFromId(
    #[GetService('entity_type.manager')]
    EntityTypeManagerInterface $entityTypeManager,
    #[OckOption('view_mode', 'View mode')]
    #[OckFormulaFromService(Formula_EntityViewMode::class)]
    string $etDotViewMode,
  ): self {
    [$type, $mode] = explode('.', $etDotViewMode);
    return new self(
      $entityTypeManager,
      $type,
      $mode,
    );
  }

  /**
   * @param string $entityType
   *
   * @return string|null
   */
  protected function etGetViewMode(string $entityType): ?string {

    if ($entityType !== $this->entityType) {
      return NULL;
    }

    return $this->viewMode;
  }
}
