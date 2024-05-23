<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityBuildProcessor;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityImage\EntityImageInterface;
use Drupal\renderkit\Html\HtmlTagTrait;

/**
 * @CfrPlugin(
 *   id = "entityBackgroundImageWrapper",
 *   label = @t("Entity background image wrapper")
 * )
 */
class EntityBuildProcessor_Wrapper_BackgroundImage implements EntityBuildProcessorInterface {

  use HtmlTagTrait;

  /**
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $imageProvider
   */
  public function __construct(
    private readonly EntityImageInterface $imageProvider,
  ) {}

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Modified render array for the given entity.
   *
   * @see \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface::processEntityBuild()
   */
  public function processEntityBuild(array $build, EntityInterface $entity): array {
    $imageDisplay = $this->imageProvider->buildEntity($entity);
    $build = $this->buildContainer() + ['content' => $build];
    if (isset($imageDisplay['#path'])) {
      $imageUrl = file_create_url($imageDisplay['#path']);
      $build['#attributes']['style'] = 'background-image: url(' . json_encode($imageUrl) . ')';
    }
    return $build;
  }
}
