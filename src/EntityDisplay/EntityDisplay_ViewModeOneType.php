<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Ock\Formula\ValueToValue\Formula_ValueToValue_CallbackMono;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\renderkit\Formula\Formula_EntityViewMode;

/**
 * Renders the entity with a view mode, but only if it has the expected type.
 */
class EntityDisplay_ViewModeOneType extends EntityDisplay_ViewModeBase {

  /**
   * @CfrPlugin(
   *   id = "viewMode",
   *   label = @t("View mode")
   * )
   *
   * @param string|null $entityType
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createFormula($entityType = NULL): FormulaInterface {

    /** @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entityDisplayRepository */
    $entityDisplayRepository = \Drupal::service('entity_display.repository');

    /** @var \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository */
    $entityTypeRepository = \Drupal::service('entity_type.repository');

    return self::doCreateFormula(
      $entityDisplayRepository,
      $entityTypeRepository,
      $entityType);
  }

    /**
     * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entityDisplayRepository
     * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository
     * @param string|null $entityType
     *
     * @return \Donquixote\Ock\Core\Formula\FormulaInterface
     */
  public static function doCreateFormula(
    EntityDisplayRepositoryInterface $entityDisplayRepository,
    EntityTypeRepositoryInterface $entityTypeRepository,
    $entityType = NULL
  ): FormulaInterface {

    return Formula_ValueToValue_CallbackMono::fromStaticMethod(
      __CLASS__,
      'createFromId',
      new Formula_EntityViewMode(
        $entityDisplayRepository,
        $entityTypeRepository,
        $entityType));
  }

  /**
   * @param string $id
   *   A combination of entity type and view mode name.
   *
   * @return self
   *
   * @throws \Donquixote\Ock\Exception\EvaluatorException_IncompatibleConfiguration
   */
  public static function createFromId($id): self {

    if (!\is_string($id)) {
      throw new EvaluatorException_IncompatibleConfiguration(
        "Id must be a string.");
    }

    if ('' === $id) {
      throw new EvaluatorException_IncompatibleConfiguration(
        "Id must not be empty.");
    }

    [$type, $mode] = explode(':', $id . ':');

    if ('' === $type) {
      throw new EvaluatorException_IncompatibleConfiguration(
        "Entity type must not be empty.");
    }

    if ('' === $mode) {
      throw new EvaluatorException_IncompatibleConfiguration(
        "View mode name must not be empty.");
    }

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager */
    $entityTypeManager = \Drupal::service('entity_type.manager');

    return new self(
      $entityTypeManager,
      $type,
      $mode);
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entityType
   * @param string $viewMode
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    private $entityType,
    private $viewMode
  ) {
    parent::__construct($entityTypeManager);
  }

  /**
   * @param string $entityType
   *
   * @return string|null
   */
  protected function etGetViewMode($entityType): ?string {

    if ($entityType !== $this->entityType) {
      return NULL;
    }

    return $this->viewMode;
  }
}
