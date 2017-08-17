<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Form\D8\FormatorD8Interface;
use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;

class CfSchema_EntityId implements CfSchema_IdInterface, FormatorD8Interface {

  /**
   * @var string
   */
  private $entityTypeId;

  /**
   * @param string $entityTypeId
   */
  public function __construct($entityTypeId) {
    $this->entityTypeId = $entityTypeId;
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return NULL !== $this->idGetEntity($id);
  }

  /**
   * @param string|int $id
   *
   * @return string
   */
  public function idGetLabel($id) {

    if (NULL === $entity = $this->idGetEntity($id)) {
      return NULL;
    }

    return $entity->label();
  }

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array|null
   */
  public function confGetD8Form($conf, $label) {

    $entity = NULL;
    if (is_string($conf) || is_int($conf)) {
      $entity = $this->idGetEntity($conf);
    }

    return [
      '#title' => $label,
      '#type' => 'entity_autocomplete',
      '#target_type' => $this->entityTypeId,
      '#default_value' => $entity,
      '#required' => TRUE,
    ];
  }

  /**
   * @param string|int $id
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  private function idGetEntity($id) {

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    $etm = \Drupal::service('entity_type.manager');

    $storage = $etm->getStorage($this->entityTypeId);

    return $storage->load($id);
  }
}
