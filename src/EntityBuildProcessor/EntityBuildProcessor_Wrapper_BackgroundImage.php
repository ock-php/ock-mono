<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityBuildProcessor;


use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit8\EntityImage\EntityImageInterface;
use Drupal\renderkit8\Html\HtmlTagTrait;

/**
 * @CfrPlugin(
 *   id = "entityBackgroundImageWrapper",
 *   label = @t("Entity background image wrapper")
 * )
 */
class EntityBuildProcessor_Wrapper_BackgroundImage implements EntityBuildProcessorInterface {

  use HtmlTagTrait;

  /**
   * @var \Drupal\renderkit8\EntityImage\EntityImageInterface
   */
  private $imageProvider;

  /**
   * @param \Drupal\renderkit8\EntityImage\EntityImageInterface $imageProvider
   */
  public function __construct(EntityImageInterface $imageProvider) {
    $this->imageProvider = $imageProvider;
  }

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Modified render array for the given entity.
   *
   * @see \Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface::processEntityBuild()
   */
  public function processEntityBuild(array $build, EntityInterface $entity) {
    $imageDisplay = $this->imageProvider->buildEntity($entity);
    $build = $this->buildContainer() + ['content' => $build];
    if (isset($imageDisplay['#path'])) {
      $imageUrl = file_create_url($imageDisplay['#path']);
      $build['#attributes']['style'] = 'background-image: url(' . json_encode($imageUrl) . ')';
    }
    return $build;
  }
}
