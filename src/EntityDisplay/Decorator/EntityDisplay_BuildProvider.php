<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\BuildProvider\BuildProviderInterface;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * @CfrPlugin("buildProvider", @t("Build provider"))
 */
class EntityDisplay_BuildProvider implements EntityDisplayInterface {

  /**
   * @var \Drupal\renderkit\BuildProvider\BuildProviderInterface
   */
  private $buildProvider;

  /**
   * @param \Drupal\renderkit\BuildProvider\BuildProviderInterface $buildProvider
   */
  public function __construct(BuildProviderInterface $buildProvider) {
    $this->buildProvider = $buildProvider;
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntities(array $entities): array {
    $element = $this->buildProvider->build();
    return array_fill_keys(array_keys($entities), $element);
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntity(EntityInterface $entity): array {
    return $this->buildProvider->build();
  }
}
