<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Form\D8\FormatorD8Interface;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;

class Formula_EntityId implements Formula_IdInterface, FormatorD8Interface {

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
  public function idIsKnown($id): bool {
    return NULL !== $this->idGetEntity($id);
  }

  /**
   * {@inheritdoc}
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
   * @return array
   */
  public function confGetD8Form($conf, $label): array {

    $entity = NULL;
    if (\is_string($conf) || \is_int($conf)) {
      $entity = $this->idGetEntity($conf);
    }

    return [
      '#title' => $label,
      /* @see \Drupal\Core\Entity\Element\EntityAutocomplete */
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

    try {
      $storage = $etm->getStorage($this->entityTypeId);
    }
    catch (InvalidPluginDefinitionException $e) {
      // @todo Log this.
      unset($e);
      return null;
    }

    return $storage->load($id);
  }
}
