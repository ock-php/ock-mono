<?php

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_CallbackMono;
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
   * @param string $entityType
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createSchema($entityType = NULL) {

    return CfSchema_ValueToValue_CallbackMono::fromStaticMethod(
      __CLASS__,
      'createFromId',
      new CfSchema_EntityTypeWithViewModeName($entityType));
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

    if (!is_string($id)) {
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

    return new self($type, $mode);
  }

  /**
   * @param string $entityType
   * @param string $viewMode
   */
  public function __construct($entityType, $viewMode) {
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
