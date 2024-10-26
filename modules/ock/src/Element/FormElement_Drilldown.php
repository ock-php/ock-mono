<?php
declare(strict_types=1);

namespace Drupal\ock\Element;

use Drupal\Core\Render\Attribute\FormElement;
use Drupal\Core\Render\Element\FormElementBase;

/**
 * @todo Not used currently.
 */
#[FormElement('ock_drilldown')]
class FormElement_Drilldown extends FormElementBase {

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return [
      /* @see _ock_id_conf_element_process() */
      '#process' => ['_ock_id_conf_element_process'],
      '#theme_wrappers' => ['themekit_container'],
      '#ock_confToForm' => NULL,
    ];
  }

}
