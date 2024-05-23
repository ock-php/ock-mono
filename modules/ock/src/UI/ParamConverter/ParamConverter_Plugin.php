<?php
declare(strict_types=1);

namespace Drupal\ock\UI\ParamConverter;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\Ock\Exception\PluginListException;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;
use Donquixote\Ock\Plugin\NamedPlugin;
use Drupal\ock\Attribute\DI\ServiceTags;

#[Service(self::class)]
#[ServiceTags(['paramconverter'])]
class ParamConverter_Plugin extends ParamConverterBase {

  public const TYPE = 'ock:plugin';

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $pluginMap
   */
  public function __construct(
    #[GetService]
    private readonly PluginMapInterface $pluginMap,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults): ?NamedPlugin {
    $interface = $defaults['interface'] ?? '';
    if ($interface === '') {
      return null;
    }
    if (!is_string($interface)) {
      throw new \RuntimeException('Interface must be a string.');
    }
    try {
      $plugins = $this->pluginMap->typeGetPlugins($interface);
    }
    catch (PluginListException $e) {
      \watchdog_exception('ock', $e);
    }
    $plugin = $plugins[$value] ?? null;
    if (!$plugin) {
      return null;
    }

    return new NamedPlugin($value, $plugin);
  }
}
