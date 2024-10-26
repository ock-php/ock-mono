<?php

declare(strict_types = 1);

namespace Drupal\ock_preset\FactsIA;

use Drupal\ock_preset\Crud\PresetRepository;
use Drupal\ock_preset\Formula\Formula_PresetProxy;
use Ock\ClassDiscovery\FactsIA\FactsIAInterface;
use Ock\DependencyInjection\Attribute\ServiceTag;
use Ock\Ock\OckPackage;
use Ock\Ock\Plugin\Plugin;
use Ock\Ock\Plugin\PluginDeclaration;
use Ock\Ock\Text\Text;

/**
 * @template-implements FactsIAInterface<int, \Ock\Ock\Plugin\PluginDeclaration>
 */
#[ServiceTag(OckPackage::DISCOVERY_TAG_NAME)]
class FactsIA_PresetPluginDeclarations implements FactsIAInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\ock_preset\Crud\PresetRepository $presetRepository
   */
  public function __construct(
    private readonly PresetRepository $presetRepository,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->presetRepository->loadAll() as $interface => $configs) {
      foreach ($configs as $id => $config) {
        $label = Text::t('Preset: @name', [
          // For now, don't translate preset labels.
          '@name' => Text::s($config->get('label')),
        ]);
        $formula = new Formula_PresetProxy($interface, $id);
        $plugin = new Plugin($label, null, $formula, []);
        yield new PluginDeclaration(
          $id,
          [$interface],
          $plugin,
        );
      }
    }
  }

}
