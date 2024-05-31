<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\renderkit\TextLookup\TextLookup_EntityType;

/**
 * Base formula to choose a view mode or form mode.
 */
abstract class Formula_EntityDisplayModeBase extends Formula_ConfigEntityIdGrouped {

  /**
   * Constructor.
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    TextLookup_EntityType $entityTypeLabelLookup,
  ) {
    $storage = $entityTypeManager->getStorage(static::getEntityTypeId());
    \assert($storage instanceof ConfigEntityStorageInterface);
    parent::__construct(
      $storage,
      FALSE,
      $entityTypeLabelLookup,
    );
  }

  /**
   * Gets the entity type id.
   *
   * @return string
   *   Should be one of 'entity_view_mode' or 'entity_form_mode'.
   */
  abstract protected static function getEntityTypeId(): string;

  /**
   * @param string $entityType
   *
   * @return static
   */
  public function withEntityType(string $entityType): static {
    return $this->withProperty('targetEntityType', $entityType);
  }

}
