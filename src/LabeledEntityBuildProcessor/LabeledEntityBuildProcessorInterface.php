<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledEntityBuildProcessor;

use Drupal\Core\Entity\EntityInterface;

interface LabeledEntityBuildProcessorInterface {

  /**
   * @param array $build
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param string $label
   *
   * @return array
   */
  public function buildAddLabelWithEntity(array $build, EntityInterface $entity, $label);

}
