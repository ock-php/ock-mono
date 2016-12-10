<?php

namespace Drupal\renderkit\EntityDisplay\Group;

use Drupal\cfrapi\Configurator\Sequence\Configurator_Sequence;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\cfrreflection\Configurator\Configurator_CallbackMono;
use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * A group of entity display handlers, whose results are assembled into a single
 * render array.
 *
 * This can be used for something like a layout region with a number of fields
 * or elements.
 */
class EntityDisplay_GroupOfDisplays extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   */
  protected $displayHandlers;

  /**
   * @CfrPlugin("sequence", @t("Sequence of entity displays"))
   *
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator(CfrContextInterface $context = NULL) {
    $displayConfigurator = cfrplugin()->interfaceGetOptionalConfigurator(EntityDisplayInterface::class, $context);
    $sequenceConfigurator = new Configurator_Sequence($displayConfigurator);
    return Configurator_CallbackMono::createFromClassName(__CLASS__, $sequenceConfigurator);
  }

  /**
   * @CfrPlugin(
   *   id = "group",
   *   label = "(deprecated) Sequence of entity displays"
   * )
   *
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createPlugin(CfrContextInterface $context = NULL) {
    $displayConfigurator = cfrplugin()->interfaceGetOptionalConfigurator(EntityDisplayInterface::class, $context);
    $configurators = [new Configurator_Sequence($displayConfigurator)];
    $labels = [''];
    return Configurator_CallbackConfigurable::createFromClassName(__CLASS__, $configurators, $labels);
  }

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[] $displayHandlers
   */
  public function __construct(array $displayHandlers) {
    foreach ($displayHandlers as $delta => $displayHandler) {
      if (!$displayHandler instanceof EntityDisplayInterface) {
        unset($displayHandlers[$delta]);
        break;
      }
    }
    $this->displayHandlers = $displayHandlers;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  public function buildEntities($entityType, array $entities) {
    $builds = [];
    foreach ($this->displayHandlers as $name => $handler) {
      foreach ($handler->buildEntities($entityType, $entities) as $delta => $entity_build) {
        unset($entity_build['#weight']);
        $builds[$delta][$name] = $entity_build;
      }
    }
    return $builds;
  }
}
