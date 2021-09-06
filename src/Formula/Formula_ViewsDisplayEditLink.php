<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Form\D8\FormatorD8Interface;
use Donquixote\ObCK\IdToFormula\IdToFormula_Callback;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_Null;
use Drupal\Core\Link;

class Formula_ViewsDisplayEditLink extends Formula_ValueProvider_Null implements FormatorD8Interface {

  /**
   * @var string
   */
  private $viewId;

  /**
   * @var string
   */
  private $displayId;

  /**
   * @param \Drupal\renderkit\Formula\Formula_ViewIdWithDisplayId $idFormula
   *
   * @return \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function createDrilldown(Formula_ViewIdWithDisplayId $idFormula) {
    return Formula_Drilldown::create(
      $idFormula,
      new IdToFormula_Callback([self::class, 'fromCompositeId']));
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
  public function confGetD8Form($conf, $label): array {

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
