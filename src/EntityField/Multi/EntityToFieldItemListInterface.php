<?php

namespace Drupal\renderkit8\EntityField\Multi;

use Drupal\Core\Entity\FieldableEntityInterface;

interface EntityToFieldItemListInterface {

  /**
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *
   * @return \Drupal\Core\Field\FieldItemListInterface|null
   */
  public function entityGetItemList(FieldableEntityInterface $entity);

}
