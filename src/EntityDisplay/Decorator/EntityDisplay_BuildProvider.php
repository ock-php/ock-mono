<?php

namespace Drupal\renderkit8\EntityDisplay\Decorator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit8\BuildProvider\BuildProviderInterface;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;

/**
 * @CfrPlugin("buildProvider", @t("Build provider"))
 */
class EntityDisplay_BuildProvider implements EntityDisplayInterface {

  /**
   * @var \Drupal\renderkit8\BuildProvider\BuildProviderInterface
   */
  private $buildProvider;

  /**
   * @param \Drupal\renderkit8\BuildProvider\BuildProviderInterface $buildProvider
   */
  public function __construct(BuildProviderInterface $buildProvider) {
    $this->buildProvider = $buildProvider;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   */
  public function buildEntities(array $entities) {
    $element = $this->buildProvider->build();
    return array_fill_keys(array_keys($entities), $element);
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity) {
    return $this->buildProvider->build();
  }
}
