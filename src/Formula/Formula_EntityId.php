<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Formula\IdToLabel\Formula_IdToLabelInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Entity\EntityInterface;
use Drupal\cu\Formator\FormatorD8Interface;

class Formula_EntityId implements Formula_IdToLabelInterface, FormatorD8Interface {

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
  public function idGetLabel($id): ?TextInterface {

    if (NULL === $entity = $this->idGetEntity($id)) {
      return NULL;
    }

    return Text::s($entity->label());
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
  private function idGetEntity($id): ?EntityInterface {

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    $etm = \Drupal::service('entity_type.manager');

    try {
      $storage = $etm->getStorage($this->entityTypeId);
    }
    catch (PluginException $e) {
      // @todo Log this.
      unset($e);
      return NULL;
    }

    return $storage->load($id);
  }
}
