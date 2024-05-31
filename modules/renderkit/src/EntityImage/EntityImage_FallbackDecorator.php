<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityImage;

use Drupal\Core\Entity\EntityInterface;
use Ock\Ock\Attribute\Parameter\OckDecorated;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * @todo Mark as decorator?
 */
#[OckPluginInstance('fallback', 'Fallback')]
class EntityImage_FallbackDecorator implements EntityImageInterface {

  /**
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $decorated
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $fallback
   */
  public function __construct(
    #[OckDecorated]
    private readonly EntityImageInterface $decorated,
    #[OckOption('fallback', 'Fallback')]
    private readonly EntityImageInterface $fallback,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildEntity(EntityInterface $entity): array {
    $build = $this->decorated->buildEntity($entity);
    if (\is_array($build) && [] !== $build) {
      return $build;
    }
    return $this->fallback->buildEntity($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntities(array $entities): array {
    $builds = array_fill_keys(array_keys($entities), NULL);
    $builds += $this->decorated->buildEntities($entities);
    foreach (array_filter($this->decorated->buildEntities($entities)) as $delta => $build) {
      unset($entities[$delta]);
      $builds[$delta] = $build;
    }
    foreach (array_filter($this->fallback->buildEntities($entities)) as $delta => $build) {
      $builds[$delta] = $build;
    }
    return array_filter($builds);
  }

}
