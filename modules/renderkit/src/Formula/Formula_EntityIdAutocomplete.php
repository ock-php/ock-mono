<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Ock\DID\Attribute\Parameter\CallServiceWithArguments;
use Ock\DID\Attribute\ParametricService;
use Ock\Ock\Exception\FormulaException;
use Ock\Ock\Formula\IdToLabel\Formula_IdToLabelInterface;
use Ock\Ock\Text\TextInterface;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ock\DrupalText;
use Drupal\ock\Formator\FormatorD8Interface;

#[ParametricService]
class Formula_EntityIdAutocomplete implements Formula_IdToLabelInterface, FormatorD8Interface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   */
  public function __construct(
    #[CallServiceWithArguments]
    private readonly EntityStorageInterface $storage,
  ) {}

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entityTypeId
   *
   * @return self
   *
   * @throws \Ock\Ock\Exception\FormulaException
   */
  public static function create(
    EntityTypeManagerInterface $entityTypeManager,
    string $entityTypeId,
  ): self {
    try {
      $storage = $entityTypeManager->getStorage($entityTypeId);
    }
    catch (PluginException $e) {
      throw new FormulaException("Entity type '$entityTypeId' was not found.", 0, $e);
    }
    return new self($storage);
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {
    return NULL !== $this->idGetEntity($id);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    return DrupalText::fromEntityOrNull($this->idGetEntity($id));
  }

  /**
   * @param mixed $conf
   * @param \Drupal\Component\Render\MarkupInterface|string|null $label
   *
   * @return array
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {

    $entity = NULL;
    if (\is_string($conf) || \is_int($conf)) {
      $entity = $this->idGetEntity($conf);
    }

    return [
      '#title' => $label,
      /* @see \Drupal\Core\Entity\Element\EntityAutocomplete */
      '#type' => 'entity_autocomplete',
      '#target_type' => $this->storage->getEntityTypeId(),
      '#default_value' => $entity,
      '#required' => TRUE,
      '#_value_callback' => function (array &$element, mixed $input, FormStateInterface $form_state) {
        if (!$form_state->getUserInput()) {
          // Return NULL to use default value.
          return NULL;
        }
        return $input;
      },
    ];
  }

  /**
   * @param string|int $id
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  private function idGetEntity(string|int $id): ?EntityInterface {
    return $this->storage->load($id);
  }

}
