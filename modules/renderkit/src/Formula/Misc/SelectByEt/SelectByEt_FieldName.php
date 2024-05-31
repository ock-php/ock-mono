<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula\Misc\SelectByEt;

class SelectByEt_FieldName extends SelectByEt_FieldName_Base {

  /**
   * @return string
   */
  public function getCacheId(): string {
    return static::class;
  }

}
