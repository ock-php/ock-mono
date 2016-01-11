<?php

namespace Drupal\renderkit\EntityDisplay;

use Drupal\renderkit\Util\EntityUtil;
use Drupal\renderkit\EnumMap\EnumMap_EntityViewMode;
use Drupal\cfrapi\Configurator\Id\Configurator_LegendSelect;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;

class EntityDisplay_ViewMode extends EntitiesDisplayBase {

  /**
   * @var string
   */
  protected $viewMode;

  /**
   * @CfrPlugin(
   *   id = "viewMode",
   *   label = @t("View mode")
   * )
   *
   * @param string $entityType
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createConfigurator($entityType) {
    $legend = new EnumMap_EntityViewMode($entityType);
    $configurators = array(Configurator_LegendSelect::createRequired($legend));
    $labels = array(t('View mode'));
    return Configurator_CallbackConfigurable::createFromClassName(__CLASS__, $configurators, $labels);
  }

  /**
   * @param string $viewMode
   */
  function __construct($viewMode) {
    $this->viewMode = $viewMode;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   *
   * @see entity_view()
   * @see node_view_multiple()
   */
  function buildEntities($entityType, array $entities) {
    if (empty($entities)) {
      // entity_view() does not like an empty array of entities.
      // Especially, node_view_multiple() really does not.
      return array();
    }
    $builds_by_type = entity_view($entityType, $entities, $this->viewMode);
    $builds_by_etid = $builds_by_type[$entityType];
    $builds_by_delta = array();

    foreach (EntityUtil::entitiesGetIds($entityType, $entities) as $delta => $etid) {
      if (isset($builds_by_etid[$etid])) {
        $builds_by_delta[$delta] = $builds_by_etid[$etid];
      }
    }

    return $builds_by_delta;
  }
}
