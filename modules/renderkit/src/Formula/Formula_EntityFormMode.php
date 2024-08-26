<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Ock\DependencyInjection\Attribute\Service;

/**
 * Formula to choose a form mode.
 */
#[Service]
class Formula_EntityFormMode extends Formula_EntityDisplayModeBase {

  /**
   * {@inheritdoc}
   */
  protected static function getEntityTypeId(): string {
    return 'entity_form_mode';
  }

}
