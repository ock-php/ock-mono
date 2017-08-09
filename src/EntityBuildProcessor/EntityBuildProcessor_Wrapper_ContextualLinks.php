<?php

namespace Drupal\renderkit8\EntityBuildProcessor;

use Donquixote\Cf\Schema\Group\CfSchema_Group;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_CallbackMono;
use Drupal\renderkit8\Html\HtmlAttributesInterface;
use Drupal\renderkit8\Html\HtmlTagTrait;
use Drupal\renderkit8\Schema\CfSchema_ClassAttribute;
use Drupal\renderkit8\Schema\CfSchema_TagName;

/**
 * A typical entity container with contextual links and stuff.
 *
 * @CfrPlugin(
 *   id = "contextualLinksWrapperDefault",
 *   label = "Entity contextual links wrapper, default"
 * )
 */
class EntityBuildProcessor_Wrapper_ContextualLinks extends EntityBuildProcessorBase implements HtmlAttributesInterface {

  use HtmlTagTrait;

  /**
   * @CfrPlugin("contextualLinksWrapper", "Entity contextual links wrapper")
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createCfrSchema() {

    $groupSchema = new CfSchema_Group(
      [
        'tag_name' => CfSchema_TagName::createForContainer('article'),
        'classes' => CfSchema_ClassAttribute::create(),
      ],
      [
        'tag_name' => t('Tag name'),
        'classes' => t('Classes'),
      ]);

    return CfSchema_ValueToValue_CallbackMono::fromStaticMethod(
      __CLASS__,
      'createFromGroupValues',
      $groupSchema);
  }

  /**
   * @param array $values
   *
   * @return self
   */
  public static function createFromGroupValues(array $values) {
    $processor = new self();
    $processor->setTagName($values['tag_name']);
    $processor->addClasses($values['classes']);
    return $processor;
  }

  public function __construct() {
    $this->setTagName('article');
  }

  /**
   * @param array $build
   *   Render array before the processing.
   * @param string $entity_type
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Render array after the processing.
   */
  public function processEntityBuild(array $build, $entity_type, $entity) {
    $build = $this->buildContainer() + ['content' => $build];
    if (!user_access('access contextual links')) {
      return $build;
    }
    // Initialize the template variable as a renderable array.
    $entity_uri = entity_uri($entity_type, $entity);
    if (!isset($entity_uri['path'])) {
      return $build;
    }
    $entity_id = entity_id($entity_type, $entity);
    if (empty($entity_id)) {
      return $build;
    }
    if (FALSE === $pos = strpos($entity_uri['path'] . '/', '/' . $entity_id . '/')) {
      return $build;
    }
    $base_path = substr($entity_uri['path'], 0, $pos);
    $build['contextual_links'] = [
      '#type' => 'contextual_links',
      '#contextual_links' => [
        $entity_type => [$base_path, [$entity_id]],
      ],
      '#element' => $build,
    ];
    // Mark this element as potentially having contextual links attached to it.
    $build['#attributes']['class'][] = 'contextual-links-region';
    return $build;
  }
}
