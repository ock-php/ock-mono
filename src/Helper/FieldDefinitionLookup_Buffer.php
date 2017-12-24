<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Helper;

class FieldDefinitionLookup_Buffer implements FieldDefinitionLookupInterface {

  /**
   * @var \Drupal\Core\Field\FieldDefinitionInterface[][]
   */
  private $definitions = [];

  /**
   * @var true[][]
   */
  private $fieldProcessed = [];

  /**
   * @var true[]
   */
  private $etProcessed = [];

  /**
   * @var \Drupal\renderkit8\Helper\FieldDefinitionLookupInterface
   */
  private $decorated;

  /**
   * @param \Drupal\renderkit8\Helper\FieldDefinitionLookupInterface $decorated
   */
  public function __construct(FieldDefinitionLookupInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $et
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   */
  public function etGetFieldDefinitions($et) {

    if (isset($this->etProcessed[$et])) {
      return $this->definitions[$et];
    }

    $this->etProcessed[$et] = TRUE;

    return $this->definitions[$et] = $this->decorated->etGetFieldDefinitions($et);
  }

  /**
   * @param string $et
   * @param string $fieldName
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface|null
   */
  public function etAndFieldNameGetDefinition($et, $fieldName) {

    if (isset($this->fieldProcessed[$et][$fieldName])) {
      return $this->definitions[$et][$fieldName];
    }

    $this->fieldProcessed[$et][$fieldName] = TRUE;

    if (isset($this->etProcessed[$et])) {
      return NULL;
    }

    return $this->definitions[$et][$fieldName] = $this->decorated->etAndFieldNameGetDefinition(
      $et,
      $fieldName);
  }
}
