<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

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
