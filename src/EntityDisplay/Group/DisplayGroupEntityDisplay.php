<?php

namespace Drupal\renderkit\EntityDisplay\Group;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\valconfapi\Callback\Impl\ClassConstructionCallback;

use Drupal\uniplugin\UniPlugin\ArgumentHandler\TypedArrayArgumentHandler;
use Drupal\uniplugin\UniPlugin\Configurable\HandlerFactoryConfigurableUniPlugin;

/**
 * A group of entity display handlers, whose results are assembled into a single
 * render array.
 *
 * This can be used for something like a layout region with a number of fields
 * or elements.
 */
class DisplayGroupEntityDisplay extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   */
  protected $displayHandlers;

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[] $displayHandlers
   */
  function __construct(array $displayHandlers) {
    $this->displayHandlers = $displayHandlers;
  }

  /**
   * @UniPlugin(
   *   id = "group",
   *   label = "Group of entity displays"
   * )
   *
   * @return \Drupal\uniplugin\UniPlugin\UniPluginInterface
   */
  static function createPlugin() {
    $handlerFactory = ClassConstructionCallback::createFromClassName(__CLASS__);
    $itemArgumentHandler = uniplugin()->interfaceLabelGetOptionHandler(EntityDisplayInterface::class, t('Entity display plugin'));
    $argumentHandlers = array(
      'displayHandlers' => new TypedArrayArgumentHandler($itemArgumentHandler, t('Entity displays')),
    );
    return new HandlerFactoryConfigurableUniPlugin($handlerFactory, $argumentHandlers, array());
  }

  /**
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildEntities($entity_type, array $entities) {
    $builds = array();
    foreach ($this->displayHandlers as $name => $handler) {
      foreach ($handler->buildEntities($entity_type, $entities) as $delta => $entity_build) {
        unset($entity_build['#weight']);
        $builds[$delta][$name] = $entity_build;
      }
    }
    return $builds;
  }
}
