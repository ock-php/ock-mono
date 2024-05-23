<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Ock\Attribute\Parameter\OckFormulaFromClass;
use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Donquixote\Ock\Formula\Textfield\Formula_Textfield_IntegerInRange;
use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface;

class EntityDisplay_RepeatSameEntity extends EntityDisplayBase {

  /**
   * @param int|string $n
   * @param \Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface $entitiesListFormat
   *
   * @return self
   */
  #[OckPluginInstance('repeatSameEntity', 'Repeat the same entity')]
  public static function create(
    #[OckOption('repeat_count', 'Number of repetitions')]
    #[OckFormulaFromClass(Formula_Textfield_IntegerInRange::class, [1, 100])]
    int|string $n,
    #[OckOption('entities_list_format', 'Entities list format')]
    EntitiesListFormatInterface $entitiesListFormat,
  ): self {
    return new self($entitiesListFormat, (int) $n);
  }

  /**
   * @param \Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface $entitiesListFormat
   * @param int $n
   */
  public function __construct(
    private readonly EntitiesListFormatInterface $entitiesListFormat,
    private readonly int $n,
  ) {}

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity): array {
    return $this->entitiesListFormat->entitiesBuildList(
      array_fill(0, $this->n, $entity),
    );
  }
}
