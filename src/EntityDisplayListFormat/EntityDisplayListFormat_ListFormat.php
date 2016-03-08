<?php

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
  function __construct(ListFormatInterface $listFormat) {
    $this->listFormat = $listFormat;
  }

  /**
   * @param array[] $builds
   * @param string $entityType
   * @param object $entity
   *
   * @return array
   */
  function buildListWithEntity(array $builds, $entityType, $entity) {
    return $this->listFormat->buildList($builds);
  }
}
