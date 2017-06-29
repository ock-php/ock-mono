<?php

namespace Drupal\renderkit\EntityBuildProcessor;



use Drupal\cfrapi\CfrSchema\ValueToValue\ValueToValueSchema_Callback;
use Drupal\cfrapi\CfrSchema\Group\GroupSchema;
use Drupal\cfrapi\CfrSchema\Group\GroupSchema_Callback;
use Drupal\cfrapi\Configurator\Group\Configurator_GroupWithValueCallback;
use Drupal\renderkit\Configurator\Configurator_ClassAttribute;
use Drupal\renderkit\Configurator\Configurator_TagName;
use Drupal\renderkit\Html\HtmlAttributesInterface;
use Drupal\renderkit\Html\HtmlTagTrait;

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
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
   */
  public static function createCfrSchema() {

    $groupSchema = new GroupSchema(
      [
        'tag_name' => Configurator_TagName::createForContainer('article'),
        'classes' => Configurator_ClassAttribute::create(),
      ],
      [
        'tag_name' => t('Tag name'),
        'classes' => t('Classes'),
      ]);

    return ValueToValueSchema_Callback::createFromClassStaticMethod(
      __CLASS__,
      'createFromGroupValues',
      $groupSchema);
  }

  /**
   * @CfrPlugin("contextualLinksWrapper", "Entity contextual links wrapper")
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator() {

    $configurator = new Configurator_GroupWithValueCallback(
      [self::class, 'createFromGroupValues']);

    $configurator->keySetConfigurator(
      'tag_name',
      Configurator_TagName::createForContainer('article'),
      t('Tag name'));

    $configurator->keySetConfigurator(
      'classes',
      Configurator_ClassAttribute::create(),
      t('Classes'));

    return $configurator;
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
   * @param object $entity
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
