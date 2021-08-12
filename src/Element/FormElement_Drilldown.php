<?php
declare(strict_types=1);

namespace Drupal\cu\Element;

use Drupal\Core\Render\Element\FormElement;

/**
 * @todo Not used currently.
 *
 * @FormElement("cu_drilldown")
 */
class FormElement_Drilldown extends FormElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return [
      /* @see _cu_id_conf_element_process() */
      '#process' => ['_cu_id_conf_element_process'],
      '#theme_wrappers' => ['themekit_container'],
      '#cu_confToForm' => NULL,
    ];
  }
}
