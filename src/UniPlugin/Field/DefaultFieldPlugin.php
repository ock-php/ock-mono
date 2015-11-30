<?php

namespace Drupal\renderkit\UniPlugin\Field;

use Drupal\renderkit\Field\Field;
use Drupal\renderkit\FieldUtil;
use Drupal\uniplugin\Handler\BrokenUniHandler;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

/**
 * Default plugin for FieldInterface.
 *
 * @UniPlugin(
 *   label = "Field",
 *   id = "field",
 *   default = true
 * )
 */
class DefaultFieldPlugin extends ConfigurableUniPluginBase {

  /**
   * @var null|string[]
   */
  private $fieldTypes;

  /**
   * @var null|string
   */
  private $entityType;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param null|string[] $fieldTypes
   * @param string $entityType
   *   Contextual parameter.
   * @param string $bundleName
   *   Contextual parameter.
   */
  function __construct($fieldTypes = NULL, $entityType = NULL, $bundleName = NULL) {
    $this->fieldTypes = $fieldTypes;
    $this->entityType = $entityType;
    $this->bundleName = $bundleName;
  }

  /**
   * @param array $conf
   *
   * @return array
   */
  function confGetForm(array $conf) {

    $form = array();

    $options = isset($this->fieldTypes)
      ? FieldUtil::fieldTypesGetFieldNameOptions($this->fieldTypes, $this->entityType, $this->bundleName)
      : FieldUtil::etBundleGetFieldNameOptions($this->entityType, $this->bundleName);

    $currentFieldName = (isset($conf['field_name']) && isset($options[$conf['field_name']]))
      ? $conf['field_name']
      : NULL;

    $form['field_name'] = array(
      '#title' => t('Field'),
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => $currentFieldName,
      '#empty_value' => '',
    );

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

    $currentFieldName = isset($conf['field_name'])
      ? $conf['field_name']
      : NULL;

    return $currentFieldName;
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
   * @see \Drupal\renderkit\Field\FieldInterface
   */
  function confGetHandler(array $conf) {

    if (!isset($conf['field_name'])) {
      return BrokenUniHandler::createFromMessage('No field name specified.');
    }

    $currentFieldName = $conf['field_name'];

    return new Field($currentFieldName);
  }
}
