<?php

namespace Drupal\renderkit8\EntityDisplayListFormat;

use Drupal\renderkit8\ListFormat\ListFormatInterface;

/**
 * @CfrPlugin(
 *   id = "listFormat",
 *   label = @t("List format"),
 *   inline = true
 * )
 */
class EntityDisplayListFormat_ListFormat implements EntityDisplayListFormatInterface {

  /**
   * @var \Drupal\renderkit8\ListFormat\ListFormatInterface
   */
  private $listFormat;

  /**
   * @param \Drupal\renderkit8\ListFormat\ListFormatInterface $listFormat
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
  public function buildListWithEntity(array $builds, $entityType, $entity) {
    return $this->listFormat->buildList($builds);
  }
}
