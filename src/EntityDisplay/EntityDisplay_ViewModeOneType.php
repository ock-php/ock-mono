<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_CallbackMono;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\renderkit\Schema\CfSchema_EntityTypeWithViewModeName;

/**
 * Renders the entity with a view mode, but only if it has the expected type.
 */
class EntityDisplay_ViewModeOneType extends EntityDisplay_ViewModeBase {

  /**
   * @var string
   */
  private $entityType;

  /**
   * @var string
   */
  private $viewMode;

  /**
   * @CfrPlugin(
   *   id = "viewMode",
   *   label = @t("View mode")
   * )
   *
   * @param string|null $entityType
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createSchema($entityType = NULL) {

    /** @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entityDisplayRepository */
    $entityDisplayRepository = \Drupal::service('entity_display.repository');

    /** @var \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository */
    $entityTypeRepository = \Drupal::service('entity_type.repository');

    return self::doCreateSchema(
      $entityDisplayRepository,
      $entityTypeRepository,
      $entityType);
  }

    /**
     * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entityDisplayRepository
     * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository
     * @param string|null $entityType
     *
     * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
     */
  public static function doCreateSchema(
    EntityDisplayRepositoryInterface $entityDisplayRepository,
    EntityTypeRepositoryInterface $entityTypeRepository,
    $entityType = NULL
  ) {

    return CfSchema_ValueToValue_CallbackMono::fromStaticMethod(
      __CLASS__,
      'createFromId',
      new CfSchema_EntityTypeWithViewModeName(
        $entityDisplayRepository,
        $entityTypeRepository,
        $entityType));
  }

  /**
   * @param string $id
   *   A combination of entity type and view mode name.
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplay_ViewModeOneType
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration
   */
  public static function createFromId($id) {

    if (!\is_string($id)) {
      throw new EvaluatorException_IncompatibleConfiguration(
        "Id must be a string.");
    }

    if ('' === $id) {
      throw new EvaluatorException_IncompatibleConfiguration(
        "Id must not be empty.");
    }

    list($type, $mode) = explode(':', $id . ':');

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
    $entityType,
    $viewMode
  ) {
    parent::__construct($entityTypeManager);
    $this->entityType = $entityType;
    $this->viewMode = $viewMode;
  }

  /**
   * @param string $entityType
   *
   * @return string|null
   */
  protected function etGetViewMode($entityType) {

    if ($entityType !== $this->entityType) {
      return NULL;
    }

    return $this->viewMode;
  }
}
