<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

class FormatorD8_DrupalPlugin extends FormatorD8_DrilldownSelectBase {

  /**
   * @inheritDoc
   */
  protected function idIsOptionless(string $id): bool {
    return FALSE;
  }

  /**
   * @inheritDoc
   */
  protected function idGetSubform(string $id, $subConf): array {
    return [];
  }

}
