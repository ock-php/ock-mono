<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\ValueProvider\Formula_ValueProvider_Null;
use Donquixote\Ock\Text\Text;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Link;
use Drupal\ock\Formator\FormatorD8Interface;

class Formula_ViewsDisplayEditLink extends Formula_ValueProvider_Null implements FormatorD8Interface {

  /**
   * @param \Drupal\renderkit\Formula\Formula_ViewIdWithDisplayId $idFormula
   *
   * @return \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function createDrilldown(Formula_ViewIdWithDisplayId $idFormula): FormulaInterface {
    return Formula::group()
      ->add(
        'views_display',
        Text::t('Views display'),
        $idFormula,
      )
      ->addDynamicFormula(
        'views_display_edit_link',
        Text::t('Edit'),
        ['views_display'],
        [self::class, 'fromCompositeId'],
      )
      ->buildGroupFormula();
  }

  /**
   * @param string $id
   *
   * @return self|null
   */
  public static function fromCompositeId(string $id): ?self {
    [$viewId, $displayId] = explode(':', $id) + ['', ''];
    if ('' === $viewId || '' === $displayId) {
      return NULL;
    }
    return new self($viewId, $displayId);
  }

  /**
   * Constructor.
   *
   * @param string $viewId
   * @param string $displayId
   */
  public function __construct(
    private readonly string $viewId,
    private readonly string $displayId,
  ) {}

  /**
   * @param mixed $conf
   * @param \Drupal\Component\Render\MarkupInterface|string|null $label
   *
   * @return array
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {
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
