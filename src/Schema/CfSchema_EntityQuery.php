<?php

namespace Drupal\renderkit8\Schema;

class CfSchema_EntityQuery {

  public function getValue(array $conf) {

    $entityTypeId = $conf['entity_type'];

    $q = \Drupal::entityQuery($entityTypeId);

    $q->condition('', '', '=');

  }

}
