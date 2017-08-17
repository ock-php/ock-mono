<?php

namespace Drupal\renderkit8\Schema\Misc\EntityTypeCondition;

class EntityTypeCondition_EntityClassImplements implements EntityTypeConditionInterface {

  /**
   * @var string
   */
  private $classOrInterface;

  /**
   * @param string $classOrInterface
   */
  public function __construct($classOrInterface) {
    $this->classOrInterface = $classOrInterface;
  }

  /**
   * @param string $entityTypeId
   *
   * @return bool
   */
  public function checkEntityTypeId($entityTypeId) {

    $etm = \Drupal::entityTypeManager();

    if (NULL === $etDefinition = $etm->getDefinition($entityTypeId, FALSE)) {
      return FALSE;
    }

    return is_a($etDefinition->getClass(), $this->classOrInterface, TRUE);
  }
}
