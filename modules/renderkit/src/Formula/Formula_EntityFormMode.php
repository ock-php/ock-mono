<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\ock\Attribute\DI\PublicService;

/**
 * Formula to choose a form mode.
 */
#[PublicService]
class Formula_EntityFormMode extends Formula_EntityDisplayModeBase {

  /**
   * {@inheritdoc}
   */
  protected static function getEntityTypeId(): string {
    return 'entity_form_mode';
  }

}
