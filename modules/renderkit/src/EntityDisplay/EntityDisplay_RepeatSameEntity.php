<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface;
use Ock\Ock\Attribute\Parameter\OckFormulaFromClass;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Ock\Ock\Formula\Textfield\Formula_Textfield_IntegerInRange;

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
   * {@inheritdoc}
   */
  public function buildEntity(EntityInterface $entity): array {
    return $this->entitiesListFormat->entitiesBuildList(
      array_fill(0, $this->n, $entity),
    );
  }

}
