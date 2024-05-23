<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplayListFormat;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\ListFormat\ListFormatInterface;

/**
 * @CfrPlugin(
 *   id = "listFormat",
 *   label = @t("List format"),
 *   inline = true
 * )
 */
class EntityDisplayListFormat_ListFormat implements EntityDisplayListFormatInterface {

  /**
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(
    private readonly ListFormatInterface $listFormat,
  ) {}

  /**
   * @param array[] $builds
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildListWithEntity(array $builds, string $entityType, EntityInterface $entity): array {
    return $this->listFormat->buildList($builds);
  }
}
