<?php

namespace Drupal\renderkit\UniPlugin\ImageProcessor;

use Drupal\renderkit\ImageProcessor\ImageStyleImageProcessor;
use Drupal\renderkit\ImageProcessor\NeutralImageProcessor;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

/**
 * @UniPlugin(
 *   id = "imageStyle",
 *   label = @t("Image style")
 * )
 */
class ImageStyleImageProcessorPlugin extends ConfigurableUniPluginBase {

  /**
   * Builds a settings form for the plugin configuration.
   *
   * @param array $conf
   *   Current configuration. Will be empty if not configured yet.
   *
   * @return array A sub-form array to configure the plugin.
   * A sub-form array to configure the plugin.
   * @see \views_handler::options_form()
   */
  function confGetForm(array $conf) {
    $form = array();
    $form['style_name'] = array(
      '#title' => t('Image style'),
      '#type' => 'select',
      '#options' => image_style_options(),
      '#empty_option' => t('None (original image)'),
      '#default_value' => isset($conf['style_name']) ? $conf['style_name'] : NULL,
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
    if (!isset($conf['style_name'])) {
      return t('Original image.');
    }
    $styleName = $conf['style_name'];
    $styleLabels = image_style_options(FALSE, PASS_THROUGH);
    if (!isset($styleLabels[$styleName])) {
      return t('Unknown image style');
    }
    $styleLabel = $styleLabels[$styleName];
    return t('Image style: @style', array('@style' => $styleLabel));
  }

  /**
   * Gets a handler object that does the business logic.
   *
   * @param array $conf
   *   Configuration for the handler object creation, if this plugin is
   *   configurable.
   *
   * @return \Drupal\renderkit\ImageProcessor\ImageProcessorInterface|null
   *   The handler object, or NULL.
   *   Plugins should return handlers of a specific type, but they are not
   *   technically required to do this. This is why an additional check should
   *   be performed for everything returned from a plugin.
   *
   * @throws \Exception
   */
  function confGetValue(array $conf) {
    return isset($conf['style_name'])
      ? new ImageStyleImageProcessor($conf['style_name'])
      : new NeutralImageProcessor();
  }
}
