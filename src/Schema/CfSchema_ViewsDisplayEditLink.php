<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Form\D8\FormatorD8Interface;
use Donquixote\Cf\IdToSchema\IdToSchema_Callback;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown;
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
   * @param \Drupal\renderkit8\Schema\CfSchema_ViewIdWithDisplayId $idSchema
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public static function createDrilldown(CfSchema_ViewIdWithDisplayId $idSchema) {
    return CfSchema_Drilldown::create(
      $idSchema,
      new IdToSchema_Callback([self::class, 'fromCompositeId']));
  }

  /**
   * @param string $id
   *
   * @return self|null
   */
  public static function fromCompositeId($id) {

    list($viewId, $displayId) = explode(':', $id) + ['', ''];

    if ('' === $viewId || '' === $displayId) {
      return NULL;
    }

    return new self($viewId, $displayId);
  }

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
   * @return array
   */
  public function confGetD8Form($conf, ?string $label): array {

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
