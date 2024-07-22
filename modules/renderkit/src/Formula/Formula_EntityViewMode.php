<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Ock\DependencyInjection\Attribute\Service;

/**
 * Formula to choose a view mode.
 */
#[Service]
class Formula_EntityViewMode extends Formula_EntityDisplayModeBase {

  /**
   * {@inheritdoc}
   */
  protected static function getEntityTypeId(): string {
    return 'entity_view_mode';
  }

}
