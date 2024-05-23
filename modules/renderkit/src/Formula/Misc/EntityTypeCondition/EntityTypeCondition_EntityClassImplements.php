<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula\Misc\EntityTypeCondition;

class EntityTypeCondition_EntityClassImplements implements EntityTypeConditionInterface {

  /**
   * @param string $classOrInterface
   */
  public function __construct(
    private readonly string $classOrInterface,
  ) {}

  /**
   * @param string $entityTypeId
   *
   * @return bool
   */
  public function checkEntityTypeId(string $entityTypeId): bool {

    $etm = \Drupal::entityTypeManager();

    if (NULL === $etDefinition = $etm->getDefinition($entityTypeId, FALSE)) {
      return FALSE;
    }

    return is_a($etDefinition->getClass(), $this->classOrInterface, TRUE);
  }
}
