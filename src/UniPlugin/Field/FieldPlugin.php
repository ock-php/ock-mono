<?php

namespace Drupal\renderkit\UniPlugin\Field;

use Drupal\renderkit\EntityDisplay\FieldEntityDisplay;
use Drupal\renderkit\FieldFormatter\FieldDisplayDefinitionInterface;
use Drupal\uniplugin\HandlerMap\UniHandlerMapInterface;
use Drupal\valconfcontext\Context\Impl\CfrContext;
use Drupal\uniplugin\UniPlugin\Broken\BrokenUniPlugin;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

class FieldPlugin extends ConfigurableUniPluginBase {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var \Drupal\uniplugin\HandlerMap\UniHandlerMapInterface
   */
  private $formatterHandlerMap;

  /**
   * @param string $fieldName
   * @param string|null $entityType
   *   Contextual parameter.
   * @param string|null $bundleName
   *   Contextual parameter.
   *
   * @return null|static
   */
  static function create($fieldName, $entityType = NULL, $bundleName = NULL) {
    $fieldInfo = field_info_field($fieldName);
    if (!isset($fieldInfo)) {
      return BrokenUniPlugin::createFromMessage('Field not found.');
    }
    if (!isset($fieldInfo['bundles'])) {
      return BrokenUniPlugin::createFromMessage('No instances specified for field.');
    }
    if (isset($entityType)) {
      if (!isset($fieldInfo['bundles'][$entityType])) {
        return BrokenUniPlugin::createFromMessage('No field instances for entity type: ' . $entityType);
      }
      if (isset($bundleName)) {
        if (!in_array($bundleName, $fieldInfo['bundles'][$entityType])) {
          return BrokenUniPlugin::createFromMessage('No field instances for entity type bundle: ' . $entityType . ':' . $bundleName);
        }
      }
    }
    $context = CfrContext::create()
      ->paramNameSetValue('fieldName', $fieldName)
      ->paramNameSetValue('field_name', $fieldName);
    # \Drupal\krumong\dpm(uniplugin()->interfaceGetPluginTypeDIC(FieldDisplayDefinitionInterface::class)->definitionsById->getDefinitionsById());
    $formatterHandlerMap = uniplugin()->interfaceContextGetHandlerMap(FieldDisplayDefinitionInterface::class, $context);
    return new static($fieldName, $formatterHandlerMap);
  }

  /**
   * @param string $fieldName
   * @param \Drupal\uniplugin\HandlerMap\UniHandlerMapInterface $formatterHandlerMap
   */
  function __construct($fieldName, UniHandlerMapInterface $formatterHandlerMap) {
    $this->fieldName = $fieldName;
    $this->formatterHandlerMap = $formatterHandlerMap;
  }

  /**
   * @param array $conf
   *
   * @return array
   */
  function confGetForm(array $conf) {
    $conf += array('formatter' => array());
    $form = array();
    $form['formatter'] = $this->formatterHandlerMap->confGetForm($conf['formatter']);
    $form['formatter']['#title'] = t('Formatter');
    return $form;
  }

  /**
   * @param array $conf
   *   Plugin configuration.
   * @param string $pluginLabel
   *   Label from the plugin definition.
   *
   * @return string|null
   */
  function confGetSummary(array $conf, $pluginLabel = NULL) {
    $conf += array('formatter' => array());
    return $this->formatterHandlerMap->confGetSummary($conf['formatter']);
  }

  /**
   * Gets a handler object that does the business logic, or null, or dummy
   * object.
   *
   * @param array $conf
   *   Configuration for the handler object creation, if this plugin is
   *   configurable.
   *
   * @return object|null
   *   The handler object, or a dummy handler object, or NULL.
   *   Plugins should return handlers of a specific type, but they are not
   *   technically required to do this. This is why an additional check should
   *   be performed for everything returned from a plugin.
   *
   * @throws \Exception
   *
   * @see \Drupal\uniplugin\Handler\BrokenUniHandlerInterface
   */
  function confGetValue(array $conf) {
    $conf += array('formatter' => array());
    $displayDefinition = $this->formatterHandlerMap->confGetHandler($conf['formatter']);
    if (!$displayDefinition instanceof FieldDisplayDefinitionInterface) {
      return NULL;
    }
    $display = $displayDefinition->getInfo();
    return new FieldEntityDisplay($this->fieldName, $display);
  }
}
