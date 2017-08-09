<?php

namespace Drupal\renderkit8\EntityBuildProcessor;


use Drupal\renderkit8\EntityImage\EntityImageInterface;
use Drupal\renderkit8\Html\HtmlAttributesInterface;
use Drupal\renderkit8\Html\HtmlTagTrait;

/**
 * @CfrPlugin(
 *   id = "entityBackgroundImageWrapper",
 *   label = @t("Entity background image wrapper")
 * )
 */
class EntityBuildProcessor_Wrapper_BackgroundImage extends EntityBuildProcessorBase implements HtmlAttributesInterface {

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
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Modified render array for the given entity.
   *
   * @see \Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface::processEntityBuild()
   */
  public function processEntityBuild(array $build, $entity_type, $entity) {
    $imageDisplay = $this->imageProvider->buildEntity($entity);
    $build = $this->buildContainer() + ['content' => $build];
    if (isset($imageDisplay['#path'])) {
      $imageUrl = file_create_url($imageDisplay['#path']);
      $build['#attributes']['style'] = 'background-image: url(' . json_encode($imageUrl) . ')';
    }
    return $build;
  }
}
