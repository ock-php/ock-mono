<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\ock\Attribute\DI\PublicService;

/**
 * Formula to choose a view mode.
 */
#[PublicService]
class Formula_EntityViewMode extends Formula_EntityDisplayModeBase {

  /**
   * {@inheritdoc}
   */
  protected static function getEntityTypeId(): string {
    return 'entity_view_mode';
  }

}
