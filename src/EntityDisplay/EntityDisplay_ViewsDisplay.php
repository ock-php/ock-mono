<?php

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface;
use Drupal\renderkit\Schema\CfSchema_ViewsDisplayId_Entity;

/**
 * Show a view (from "views" module) for the entity.
 */
class EntityDisplay_ViewsDisplay extends EntityDisplayBase {

  /**
   * @var string
   */
  private $viewName;

  /**
   * @var string
   */
  private $displayId;

  /**
   * @var \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface
   */
  private $labeledDisplay;

  /**
   * @CfrPlugin(
   *   id = "viewsDisplay",
   *   label = @t("Views display")
   * )
   *
   * @param string|null $entityType
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createSchema($entityType = NULL) {

    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        new CfSchema_ViewsDisplayId_Entity($entityType),
        CfSchema_IfaceWithContext::createOptional(LabeledEntityBuildProcessorInterface::class),
      ],
      [
        t('Views display'),
        t('Label format'),
      ]);
  }

  /**
   * @param string $id
   * @param \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface|null $labeledEntityBuildProcessor
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplay_ViewsDisplay|null
   */
  public static function create($id, LabeledEntityBuildProcessorInterface $labeledEntityBuildProcessor = NULL) {

    list($view_name, $display_id) = explode(':', $id . ':');

    if ('' === $view_name || '' === $display_id) {
      return NULL;
    }

    // No further checking at this point.
    return new self(
      $view_name,
      $display_id,
      $labeledEntityBuildProcessor);
  }

  /**
   * @param string $viewName
   * @param string $displayId
   * @param \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface $labeledDisplay
   */
  public function __construct($viewName, $displayId, LabeledEntityBuildProcessorInterface $labeledDisplay = NULL) {
    $this->viewName = $viewName;
    $this->displayId = $displayId;
    $this->labeledDisplay = $labeledDisplay;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param string $entity_type
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   *
   * @see \Drupal\renderkit\EntityDisplay\EntityDisplayInterface::buildEntity()
   */
  public function buildEntity($entity_type, $entity) {
    $etid = $this->entityGetId($entity_type, $entity);
    if (NULL === $etid) {
      return [];
    }
    $view = \views_get_view($this->viewName);
    if (NULL === $view) {
      return [];
    }
    $success = $view->set_display($this->displayId);
    if (FALSE === $success) {
      return [];
    }
    $view->set_arguments([$etid]);
    // See https://www.drupal.org/node/525592#comment-1833824
    $view->override_path = $_GET['q'];
    $markup = $view->preview();
    if (FALSE === $markup) {
      return [];
    }
    $build = ['#markup' => $markup];
    if (NULL === $this->labeledDisplay) {
      return $build;
    }
    $label = $view->get_title();
    if (empty($label)) {
      return $build;
    }
    return $this->labeledDisplay->buildAddLabelWithEntity($build, $entity_type, $entity, $label);
  }

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return int|null
   */
  private function entityGetId($entity_type, $entity) {
    $info = entity_get_info($entity_type);
    if (empty($info['entity keys']['id'])) {
      return NULL;
    }
    $primary = $info['entity keys']['id'];
    if (empty($entity->$primary)) {
      return NULL;
    }
    $id = $entity->$primary;
    if ((string)(int)$id !== (string)$id || $id <= 0) {
      return NULL;
    }
    return (int)$id;
  }
}
