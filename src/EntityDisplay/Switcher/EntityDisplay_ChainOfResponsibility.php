<?php

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\cfrapi\Configurator\Sequence\Configurator_Sequence;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * Chain-of-responsibility entity display switcher.
 */
class EntityDisplay_ChainOfResponsibility extends EntitiesDisplayBase {

  /**
   * The displays to try.
   *
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   *   Format: $[] = $displayHandler
   */
  private $displays = array();

  /**
   * @CfrPlugin(
   *   id = "chain",
   *   label = "Chain of responsibility"
   * )
   *
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createPlugin(CfrContextInterface $context = NULL) {
    $displayConfigurator = cfrplugin()->interfaceGetOptionalConfigurator(EntityDisplayInterface::class, $context);
    $configurators = array(new Configurator_Sequence($displayConfigurator));
    $labels = array(NULL);
    return Configurator_CallbackConfigurable::createFromClassName(__CLASS__, $configurators, $labels);
  }

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[] $displays
   */
  function __construct(array $displays) {
    $this->displays = $displays;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return array[]
   */
  function buildEntities($entityType, array $entities) {
    $builds = array_fill_keys(array_keys($entities), NULL);
    foreach ($this->displays as $display) {
      foreach ($display->buildEntities($entityType, $entities) as $delta => $build) {
        if (!empty($build)) {
          if (empty($builds[$delta])) {
            $builds[$delta] = $build;
          }
          unset($entities[$delta]);
        }
      }
      /** @noinspection DisconnectedForeachInstructionInspection */
      if (array() === $entities) {
        break;
      }
    }
    return array_filter($builds);
  }

}
