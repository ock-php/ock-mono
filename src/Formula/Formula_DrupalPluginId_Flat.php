<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Component\Plugin\Discovery\DiscoveryInterface;
use Drupal\ock\DrupalText;

class Formula_DrupalPluginId_Flat implements Formula_FlatSelectInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Component\Plugin\Discovery\DiscoveryInterface $manager
   */
  public function __construct(
    private readonly DiscoveryInterface $manager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getOptions(): array {
    $definitions = $this->manager->getDefinitions();
    $options = [];
    foreach ($definitions as $id => $definition) {
      $options[$id] = DrupalText::fromVarOr($definition['label'] ?? NULL, $id);
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {
    /** @noinspection PhpUnhandledExceptionInspection */
    $definition = $this->manager->getDefinition($id, FALSE);
    if ($definition === NULL) {
      return NULL;
    }
    return DrupalText::fromVarOr($definition['label'] ?? NULL, $id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    /** @noinspection PhpUnhandledExceptionInspection */
    $definition = $this->manager->getDefinition($id, FALSE);
    return $definition !== NULL;
  }

}
