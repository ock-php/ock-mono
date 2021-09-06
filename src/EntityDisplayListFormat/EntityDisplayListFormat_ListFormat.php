<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplayListFormat;

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
   * @var \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  private $listFormat;

  /**
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(ListFormatInterface $listFormat) {
    $this->listFormat = $listFormat;
  }

  /**
   * @param array[] $builds
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildListWithEntity(array $builds, $entityType, $entity): array {
    return $this->listFormat->buildList($builds);
  }
}
