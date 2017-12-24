<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

class CfSchema_EntityQuery {

  /**
   * @param array $conf
   */
  public function getValue(array $conf) {

    $entityTypeId = $conf['entity_type'];

    $q = \Drupal::entityQuery($entityTypeId);

    $q->condition('', '', '=');

    // @todo Finish this, return something.
  }

}
