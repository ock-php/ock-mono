<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Form\D8\FormatorD8Interface;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_Null;
use Drupal\Core\Link;

class CfSchema_ViewsDisplayEditLink extends CfSchema_ValueProvider_Null implements FormatorD8Interface {

  /**
   * @var string
   */
  private $viewId;

  /**
   * @var string
   */
  private $displayId;

  /**
   * @param string $viewId
   * @param string $displayId
   */
  public function __construct($viewId, $displayId) {
    $this->viewId = $viewId;
    $this->displayId = $displayId;
  }

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array|null
   */
  public function confGetD8Form($conf, $label) {

    return [
      '#children' => Link::createFromRoute(
        'Edit view',
        'entity.view.edit_display_form',
        [
          $this->viewId,
          $this->displayId,
        ]),
    ];
  }
}
