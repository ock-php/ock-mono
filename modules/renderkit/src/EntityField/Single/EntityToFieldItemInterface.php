<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityField\Single;

use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldItemInterface;

interface EntityToFieldItemInterface {

  /**
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *
   * @return \Drupal\Core\Field\FieldItemInterface|null
   */
  public function entityGetItem(FieldableEntityInterface $entity): ?FieldItemInterface;

}
