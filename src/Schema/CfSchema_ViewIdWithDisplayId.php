<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Select\CfSchema_Select_TwoStepFlatSelectGrandBase;
use Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayCondition_And;
use Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayCondition_EntityIdArg;
use Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayCondition_NoArgs;
use Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayCondition_Status;
use Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface;

/**
 * Schema for values of the structure $view_id . ':' . $view_display_id
 */
class CfSchema_ViewIdWithDisplayId extends CfSchema_Select_TwoStepFlatSelectGrandBase {

  /**
   * @var \Drupal\renderkit8\Schema\CfSchema_ViewId
   */
  private $idSchema;

  /**
   * @var \Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface
   */
  private $condition;

  /**
   * @return \Drupal\renderkit8\Schema\CfSchema_ViewIdWithDisplayId
   */
  public static function createNoArgs() {
    return self::createWithDisplayCondition(
      new ViewsDisplayCondition_NoArgs());
  }

  /**
   * @param string|null $entityTypeId
   *
   * @return self
   */
  public static function createWithEntityIdArg($entityTypeId = NULL) {

    return self::createWithDisplayCondition(
      new ViewsDisplayCondition_EntityIdArg($entityTypeId));
  }

  /**
   * @param \Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface $condition
   *
   * @return self
   */
  public static function createWithDisplayCondition(ViewsDisplayConditionInterface $condition) {

    return new self(
      TRUE,
      new ViewsDisplayCondition_And(
        [
          new ViewsDisplayCondition_Status(TRUE),
          # new ViewsDisplayCondition_DisplayTypeWhitelist([]),
          $condition,
        ]));
  }

  /**
   * @param bool|null $status
   * @param \Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface $condition
   */
  public function __construct($status = TRUE, ViewsDisplayConditionInterface $condition) {
    $this->idSchema = new CfSchema_ViewId($status);
    $this->condition = $condition;
  }

  /**
   * @return \Drupal\renderkit8\Schema\CfSchema_ViewId
   */
  protected function getIdSchema(): CfSchema_FlatSelectInterface {
    return $this->idSchema;
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface|null
   */
  protected function idGetSubSchema($id): ?CfSchema_FlatSelectInterface {

    if (NULL === $view = $this->idGetView($id)) {
      return NULL;
    }

    return new CfSchema_ViewDisplayId_Condition(
      $view,
      $this->condition);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\views\Entity\View|null
   */
  private function idGetView($id) {

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    $etm = \Drupal::service('entity_type.manager');

    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    try {
      $storage = $etm->getStorage('view');
    }
    catch (InvalidPluginDefinitionException $e) {
      // @todo Log this.
      unset($e);
      return null;
    }

    /** @var null|\Drupal\views\Entity\View $view */
    $view = $storage->load($id);

    return $view;
  }
}
