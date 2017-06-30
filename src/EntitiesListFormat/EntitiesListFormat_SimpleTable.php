<?php

namespace Drupal\renderkit\EntitiesListFormat;

use Drupal\cfrapi\Configurator\Sequence\Configurator_Sequence;
use Drupal\cfrreflection\Configurator\Configurator_CallbackMono;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

class EntitiesListFormat_SimpleTable implements EntitiesListFormatInterface {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   */
  private $columnDisplays;

  /**
   * @CfrPlugin("simpleTable", "Simple table")
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator() {

    return Configurator_CallbackMono::createFromClassName(
      self::class,
      (new Configurator_Sequence(
        cfrplugin()->interfaceGetOptionalConfigurator(EntityDisplayInterface::class)))
        ->withItemLabelCallback(
          function($delta) {
            return (NULL === $delta)
              ? t('New column')
              : t('Column #@n', ['@n' => $delta + 1]);
          }
        ));
  }

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[] $columnDisplays
   */
  public function __construct(array $columnDisplays) {
    $this->columnDisplays = $columnDisplays;
  }

  /**
   * Displays the entities as a list, e.g. as a table.
   *
   * @param string $entityType
   * @param object[] $entities
   *
   * @return array
   *   A render array.
   */
  public function entitiesBuildList($entityType, array $entities) {

    $rows = [];
    foreach ($this->columnDisplays as $colKey => $columnDisplay) {
      foreach ($columnDisplay->buildEntities($entityType, $entities) as $rowKey => $build) {
        $rows[$rowKey][$colKey] = drupal_render($build);
      }
    }

    return [
      /* @see theme_table() */
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }
}
