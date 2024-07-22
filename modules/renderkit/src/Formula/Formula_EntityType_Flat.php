<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Ock\DependencyInjection\Attribute\Service;
use Drupal\ock\DrupalText;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Ock\Ock\Text\TextInterface;

#[Service]
class Formula_EntityType_Flat implements Formula_FlatSelectInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(
    #[GetService('entity_type.manager')]
    private readonly EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getOptions(): array {
    $options = [];
    foreach ($this->entityTypeManager->getDefinitions() as $id => $definition) {
      $options[$id] = DrupalText::fromVar($definition->getLabel() ?? $id);
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {
    $definition = $this->idGetDefinition($id);
    if ($definition === NULL) {
      return NULL;
    }
    return DrupalText::fromVar($definition->getLabel() ?? $id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    return NULL !== $this->idGetDefinition($id);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\Core\Entity\EntityTypeInterface|null
   */
  private function idGetDefinition(string $id): ?EntityTypeInterface {
    try {
      return $this->entityTypeManager->getDefinition($id, false);
    }
    catch (PluginNotFoundException $e) {
      throw new \RuntimeException('Unexpected exception', 0, $e);
    }
  }

}
