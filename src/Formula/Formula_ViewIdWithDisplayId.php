<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_TwoStepFlatSelectGrandBase;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\renderkit\Formula\Misc\ViewsDisplayCondition\ViewsDisplayCondition_And;
use Drupal\renderkit\Formula\Misc\ViewsDisplayCondition\ViewsDisplayCondition_EntityIdArg;
use Drupal\renderkit\Formula\Misc\ViewsDisplayCondition\ViewsDisplayCondition_NoArgs;
use Drupal\renderkit\Formula\Misc\ViewsDisplayCondition\ViewsDisplayCondition_Status;
use Drupal\renderkit\Formula\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface;
use Drupal\views\Entity\View;

/**
 * Formula for values of the structure "$view_id:$view_display_id".
 */
class Formula_ViewIdWithDisplayId extends Formula_Select_TwoStepFlatSelectGrandBase {

  /**
   * @var \Drupal\renderkit\Formula\Formula_ViewId
   */
  private Formula_ViewId $idFormula;

  /**
   * @return self
   */
  public static function createNoArgs(): self {
    return self::createWithDisplayCondition(
      new ViewsDisplayCondition_NoArgs());
  }

  /**
   * @param string|null $entityTypeId
   *
   * @return self
   */
  public static function createWithEntityIdArg(string $entityTypeId = NULL): self {

    return self::createWithDisplayCondition(
      new ViewsDisplayCondition_EntityIdArg($entityTypeId));
  }

  /**
   * @param \Drupal\renderkit\Formula\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface $condition
   *
   * @return self
   */
  public static function createWithDisplayCondition(ViewsDisplayConditionInterface $condition): self {
    return new self(
      TRUE,
      new ViewsDisplayCondition_And([
        new ViewsDisplayCondition_Status(TRUE),
        # new ViewsDisplayCondition_DisplayTypeWhitelist([]),
        $condition,
      ]),
    );
  }

  /**
   * @param bool $status
   * @param \Drupal\renderkit\Formula\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface $condition
   */
  public function __construct(
    $status = TRUE,
    private readonly ViewsDisplayConditionInterface $condition,
  ) {
    $this->idFormula = new Formula_ViewId($status);
  }

  /**
   * @return \Drupal\renderkit\Formula\Formula_ViewId
   */
  protected function getIdFormula(): Formula_FlatSelectInterface {
    return $this->idFormula;
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface|null
   */
  protected function idGetSubFormula(string $id): ?Formula_FlatSelectInterface {

    if (NULL === $view = $this->idGetView($id)) {
      return NULL;
    }

    return new Formula_ViewDisplayId_Condition(
      $view,
      $this->condition);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\views\Entity\View|null
   */
  private function idGetView(string $id): ?View {

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    $etm = \Drupal::service('entity_type.manager');

    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    try {
      $storage = $etm->getStorage('view');
    }
    catch (PluginException $e) {
      // @todo Log this.
      unset($e);
      return null;
    }

    /** @var null|\Drupal\views\Entity\View $view */
    $view = $storage->load($id);

    return $view;
  }
}
