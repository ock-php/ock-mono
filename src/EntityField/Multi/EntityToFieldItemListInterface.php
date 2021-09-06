<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityField\Multi;

use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldItemListInterface;

interface EntityToFieldItemListInterface {

  /**
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *
   * @return \Drupal\Core\Field\FieldItemListInterface|null
   */
  public function entityGetItemList(FieldableEntityInterface $entity): ?FieldItemListInterface;

}
